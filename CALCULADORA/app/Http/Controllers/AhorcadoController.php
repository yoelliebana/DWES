<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiService;

class AhorcadoController extends Controller
{
    private int $maxErrores = 6;

    // ── Pantalla de selección de tema ────────────────────────

    public function index()
    {
        return view('ahorcado-tema');
    }

    // ── Setup: recibe el tema, llama a Gemini, inicia partida ─

    public function setup(Request $request, GeminiService $gemini)
    {
        $request->validate([
            'tema' => ['required', 'string', 'min:2', 'max:100'],
        ], [
            'tema.required' => 'Escribe un tema para empezar.',
            'tema.min'      => 'El tema debe tener al menos 2 caracteres.',
        ]);

        $tema      = trim($request->input('tema'));
        $resultado = $gemini->generateWord($tema);

        // Si Gemini falla, usar palabra de respaldo
        if (!$resultado) {
            $resultado = [
                'palabra' => 'programacion',
                'pista'   => 'Gemini no respondió. Palabra de respaldo.',
            ];
        }

        $request->session()->put('ahorcado', [
            'palabra'       => $resultado['palabra'],
            'pista'         => $resultado['pista'],
            'tema'          => $tema,
            'letras_usadas' => [],
            'errores'       => 0,
            'ganado'        => false,
            'perdido'       => false,
        ]);

        return redirect()->route('ahorcado.jugar');
    }

    // ── Juego ────────────────────────────────────────────────

    public function jugar(Request $request)
    {
        // Si llegan aquí sin partida, volver al inicio
        if (!$request->session()->has('ahorcado')) {
            return redirect()->route('ahorcado');
        }

        return view('ahorcado', $this->estadoVista($request));
    }

    public function letra(Request $request)
    {
        $letra = strtolower(trim($request->input('letra', '')));

        if (!preg_match('/^[a-záéíóúüñ]$/u', $letra)) {
            return redirect()->route('ahorcado.jugar');
        }

        $estado = $request->session()->get('ahorcado');

        if (
            !$estado ||
            in_array($letra, $estado['letras_usadas']) ||
            $estado['ganado'] ||
            $estado['perdido']
        ) {
            return redirect()->route('ahorcado.jugar');
        }

        $estado['letras_usadas'][] = $letra;
        $palabra = $estado['palabra'];

        if (str_contains($palabra, $letra)) {
            $descubiertas = array_filter(
                mb_str_split($palabra),
                fn($c) => in_array($c, $estado['letras_usadas'])
            );
            if (count($descubiertas) === mb_strlen($palabra)) {
                $estado['ganado'] = true;
            }
        } else {
            $estado['errores']++;
            if ($estado['errores'] >= $this->maxErrores) {
                $estado['perdido'] = true;
            }
        }

        $request->session()->put('ahorcado', $estado);

        return redirect()->route('ahorcado.jugar');
    }

    public function nueva(Request $request)
    {
        $request->session()->forget('ahorcado');
        return redirect()->route('ahorcado');
    }

    // ── Helpers ──────────────────────────────────────────────

    private function estadoVista(Request $request): array
    {
        $estado  = $request->session()->get('ahorcado');
        $palabra = $estado['palabra'];
        $letras  = $estado['letras_usadas'];

        $casillas = array_map(
            fn($c) => in_array($c, $letras) ? $c : '_',
            mb_str_split($palabra)
        );

        $abecedario = mb_str_split('abcdefghijklmnñopqrstuvwxyz');

        return [
            'casillas'      => $casillas,
            'pista'         => $estado['pista'],
            'tema'          => $estado['tema'],
            'errores'       => $estado['errores'],
            'max_errores'   => $this->maxErrores,
            'letras_usadas' => $letras,
            'abecedario'    => $abecedario,
            'ganado'        => $estado['ganado'],
            'perdido'       => $estado['perdido'],
            'palabra'       => $estado['perdido'] ? $palabra : null,
        ];
    }
}