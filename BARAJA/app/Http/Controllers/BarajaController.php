<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BarajaController extends Controller
{
    private const puntos_max = 5;
    private const total_cartas = 12;

    private function barajar(): array {
        $baraja = range(1, self::total_cartas);
        shuffle($baraja);
        return $baraja;
    }

    public function index(Request $request) {
        if (!$request->session()->has('partida')) {
            $this->resetPartida($request);
        }

        $partida = $request->session()->get('partida');

        return view('baraja', [
            'cartaActual' => $partida['cartaActual'],
            'consecutiva' => $partida['consecutiva'],
            'victoria' => false,
            'resultado' => null,
            'cartaAnterior' => null,
            'intento' => null,
        ]);
    }

    public function intento(Request $request) {
        $request->validate(['intento' => 'required|in:mayor,menor']);

        $intento = $request->input('intento');
        $partida = $request->session()->get('partida');

        $cartaActual = $partida['cartaActual'];
        $baraja = $partida['baraja'];
        $index = $partida['index'];
        $consecutiva = $partida['consecutiva'];
        $nextIndex = $index + 1;
        if ($nextIndex >= count($baraja)) {
            $baraja = $this->barajar();
            $nextIndex = 0;
        }

        $nextCarta = $baraja[$nextIndex];

        $correcto = match(true) {
            $intento === 'mayor' && $nextCarta > $cartaActual => true,
            $intento === 'menor' && $nextCarta < $cartaActual => true,
            default => false,
        };

        $consecutiva = $correcto ? $consecutiva + 1 : 0;
        $victoria = $consecutiva >= self::puntos_max;

        $request->session()->put('partida', [
            'baraja' => $baraja,
            'index' => $nextIndex,
            'cartaActual' => $nextCarta,
            'consecutiva' => $victoria ? 0 : $consecutiva,
        ]);

        return view('baraja', [
            'cartaActual' => $nextCarta,
            'consecutiva' => $consecutiva,
            'victoria' => $won,
            'resultado' => $correcto ? 'acierto' : 'fallo',
            'cartaAnterior' => $cartaActual,
            'intento' => $intento,
        ]);
    }

    public function reset(Request $request) {
        $this->resetPartida($request);
        return redirect()->route('partida.index');
    }

    private function resetPartida(Request $request): void {
        $baraja = $this->barajar();
        $request->session()->put('partida', [
            'baraja' => $baraja,
            'index' => 0,
            'cartaActual' => $baraja[0],
            'consecutiva' => 0,
        ]);
    }
}
