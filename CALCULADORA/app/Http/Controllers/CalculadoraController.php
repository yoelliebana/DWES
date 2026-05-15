<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CalculadoraController extends Controller
{
    public function index(Request $request)
    {
        $n1 = $request->session()->get('n1', '0');
        $n2 = $request->session()->get('n2', '0');
        $operacion = $request->session()->get('operacion', null);
        $resultado = $request->session()->get('resultado', null);
        $error = $request->session()->get('error', null);
        $activo = $request->session()->get('activo', 'n1');

        $request->session()->forget(['error', 'resultado']);

        return view('calculadora', compact('n1', 'n2', 'operacion', 'resultado', 'error', 'activo'));
    }

    public function digito(Request $request)
    {
        $digito = $request->input('digito');
        $activo = $request->session()->get('activo', 'n1');
        $actual = $request->session()->get($activo, '0');

        if ($digito === '.' && str_contains($actual, '.')) {
            return redirect()->route('calculadora');
        }

        $nuevo = ($actual === '0' && $digito !== '.') ? $digito : $actual . $digito;

        $request->session()->put($activo, $nuevo);

        return redirect()->route('calculadora');
    }

    public function operacion(Request $request)
    {
        $op = $request->input('op');
        $ops_validas = ['suma', 'resta', 'multiplicacion', 'division', 'modulo'];

        if (!in_array($op, $ops_validas)) {
            return redirect()->route('calculadora');
        }

        $request->session()->put('operacion', $op);
        $request->session()->put('activo', 'n2');

        return redirect()->route('calculadora');
    }

    public function calcular(Request $request)
    {
        $n1 = (float) $request->session()->get('n1', 0);
        $n2 = (float) $request->session()->get('n2', 0);
        $op = $request->session()->get('operacion');

        if (!$op) {
            return redirect()->route('calculadora');
        }

        $resultado = null;
        $error     = null;

        switch ($op) {
            case 'suma':
                $resultado = $n1 + $n2;
                break;
            case 'resta':
                $resultado = $n1 - $n2;
                break;
            case 'multiplicacion':
                $resultado = $n1 * $n2;
                break;
            case 'division':
                if ($n2 == 0) {
                    $error = 'Error: división por cero';
                } else {
                    $resultado = $n1 / $n2;
                }
                break;
            case 'modulo':
                if ($n2 == 0) {
                    $error = 'Error: módulo por cero';
                } else {
                    $resultado = fmod($n1, $n2);
                }
                break;
        }

        if ($resultado !== null) {
            $resultado = (floor($resultado) == $resultado)
                ? (int) $resultado
                : round($resultado, 10);
            $request->session()->put('resultado', $resultado);
        }

        if ($error) {
            $request->session()->put('error', $error);
        }

        return redirect()->route('calculadora');
    }

    public function unaria(Request $request)
    {
        $op     = $request->input('op');
        $activo = $request->session()->get('activo', 'n1');
        $n      = (float) $request->session()->get($activo, 0);

        $resultado = null;
        $error     = null;

        switch ($op) {
            case 'raiz':
                if ($n < 0) {
                    $error = 'Error: raíz de negativo';
                } else {
                    $resultado = sqrt($n);
                }
                break;
            case 'cuadrado':
                $resultado = $n * $n;
                break;
        }

        if ($resultado !== null) {
            $resultado = (floor($resultado) == $resultado)
                ? (int) $resultado
                : round($resultado, 10);
            $request->session()->put($activo, (string) $resultado);
            $request->session()->put('resultado', $resultado);
        }

        if ($error) {
            $request->session()->put('error', $error);
        }

        return redirect()->route('calculadora');
    }

    public function activar(Request $request)
    {
        $campo = $request->input('campo');
        if (in_array($campo, ['n1', 'n2'])) {
            $request->session()->put('activo', $campo);
        }
        return redirect()->route('calculadora');
    }

    public function borrar(Request $request)
    {
        $activo = $request->session()->get('activo', 'n1');
        $actual = $request->session()->get($activo, '0');
        $nuevo  = strlen($actual) > 1 ? substr($actual, 0, -1) : '0';
        $request->session()->put($activo, $nuevo);
        return redirect()->route('calculadora');
    }

    public function limpiar(Request $request)
    {
        $request->session()->forget(['n1', 'n2', 'operacion', 'resultado', 'error', 'activo']);
        return redirect()->route('calculadora');
    }

}
