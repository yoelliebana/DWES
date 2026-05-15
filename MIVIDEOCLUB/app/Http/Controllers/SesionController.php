<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SesionController extends Controller
{
    public function index(Request $request)
    {
        $contador = $request->session()->get('contador', 1);
        return view('sesion', compact('contador'));
    }

    public function increment(Request $request)
{
    $contador = $request->session()->get('contador', 1);
    $request->session()->put('contador', $contador + 1);

    return redirect('/sesion');
}

    public function decrement(Request $request)
{
    $contador = $request->session()->get('contador', 1);
    $request->session()->put('contador', $contador - 1);

    return redirect('/sesion');
}

    public function reset(Request $request)
{
    $request->session()->forget('contador');

    return redirect('/sesion');
}
}
