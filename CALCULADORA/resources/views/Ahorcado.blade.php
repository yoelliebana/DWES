<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ahorcado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; min-height: 100vh; }

        .game-card {
            max-width: 640px;
            border-radius: 14px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
        }

        /* Horca SVG */
        .horca-wrap {
            background: #f8f9fa;
            border: 2px solid #dee2e6;
            border-radius: 10px;
            padding: 12px;
        }

        /* Casillas de la palabra */
        .casilla {
            display: inline-flex;
            align-items: flex-end;
            justify-content: center;
            width: 36px;
            height: 46px;
            border-bottom: 3px solid #495057;
            font-size: 1.5rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #212529;
            margin: 0 3px;
            line-height: 1;
        }

        .casilla.revelada { color: #dc3545; border-color: #dc3545; }

        /* Teclado de letras */
        .btn-letra {
            width: 40px;
            height: 40px;
            font-size: .9rem;
            font-weight: 600;
            padding: 0;
            border-radius: 6px;
            text-transform: uppercase;
        }

        .btn-letra:disabled {
            opacity: .35;
        }

        /* Contadores de error */
        .error-pip {
            width: 12px; height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin: 0 2px;
        }

        .alert-resultado {
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 10px;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center py-5">

<div class="card game-card p-4 w-100 mx-3">

    <h5 class="text-center text-secondary fw-normal mb-4"
        style="letter-spacing:3px; text-transform:uppercase; font-size:.85rem">
        El Ahorcado
    </h5>

    <div class="row g-4 align-items-start">

        {{-- ── Columna izquierda: horca ── --}}
        <div class="col-md-5">
            <div class="horca-wrap text-center">
                <svg viewBox="0 0 160 200" width="100%" xmlns="http://www.w3.org/2000/svg"
                     stroke="#343a40" stroke-linecap="round" fill="none">

                    {{-- Estructura fija --}}
                    <line x1="20" y1="190" x2="140" y2="190" stroke-width="4"/>
                    <line x1="60" y1="190" x2="60"  y2="10"  stroke-width="4"/>
                    <line x1="60" y1="10"  x2="110" y2="10"  stroke-width="4"/>
                    <line x1="110" y1="10" x2="110" y2="35"  stroke-width="3"/>

                    {{-- Cabeza (error 1) --}}
                    @if($errores >= 1)
                        <circle cx="110" cy="50" r="15" stroke-width="3"/>
                    @endif

                    {{-- Cuerpo (error 2) --}}
                    @if($errores >= 2)
                        <line x1="110" y1="65" x2="110" y2="120" stroke-width="3"/>
                    @endif

                    {{-- Brazo izquierdo (error 3) --}}
                    @if($errores >= 3)
                        <line x1="110" y1="80" x2="85"  y2="105" stroke-width="3"/>
                    @endif

                    {{-- Brazo derecho (error 4) --}}
                    @if($errores >= 4)
                        <line x1="110" y1="80" x2="135" y2="105" stroke-width="3"/>
                    @endif

                    {{-- Pierna izquierda (error 5) --}}
                    @if($errores >= 5)
                        <line x1="110" y1="120" x2="85"  y2="155" stroke-width="3"/>
                    @endif

                    {{-- Pierna derecha (error 6) --}}
                    @if($errores >= 6)
                        <line x1="110" y1="120" x2="135" y2="155" stroke-width="3"/>
                    @endif

                </svg>
            </div>

            {{-- Indicador de errores --}}
            <div class="text-center mt-2">
                @for($i = 1; $i <= $max_errores; $i++)
                    <span class="error-pip {{ $i <= $errores ? 'bg-danger' : 'bg-light border border-secondary' }}"></span>
                @endfor
                <div class="text-muted mt-1" style="font-size:.8rem">
                    {{ $errores }} / {{ $max_errores }} errores
                </div>
            </div>
        </div>

        {{-- ── Columna derecha: palabra + teclado ── --}}
        <div class="col-md-7">

            {{-- Resultado --}}
            @if($ganado)
                <div class="alert alert-success alert-resultado text-center mb-3">
                    🎉 ¡Enhorabuena! ¡Has ganado!
                </div>
            @elseif($perdido)
                <div class="alert alert-danger alert-resultado text-center mb-3">
                    💀 ¡Has perdido! La palabra era:
                    <span class="d-block fs-5 mt-1 text-uppercase fw-bold">{{ $palabra }}</span>
                </div>
            @endif

            {{-- Tema y pista --}}
            <div class="mb-3">
                <span class="badge bg-secondary me-1" style="font-size:.75rem">TEMA</span>
                <span class="text-muted fw-semibold" style="font-size:.9rem; text-transform:capitalize">{{ $tema }}</span>
            </div>
            <div class="alert alert-info py-2 mb-3" style="font-size:.88rem">
                💡 <strong>Pista:</strong> {{ $pista }}
            </div>

            {{-- Casillas de la palabra --}}
            <div class="mb-4 text-center" style="min-height: 56px; flex-wrap: wrap; display:flex; justify-content:center; gap:2px">
                @foreach($casillas as $c)
                    <span class="casilla {{ ($perdido && $c === '_') ? 'revelada' : '' }}">
                        {{ $c === '_' && $perdido
                            ? mb_strtoupper(mb_substr($palabra, $loop->index, 1))
                            : ($c === '_' ? '' : mb_strtoupper($c)) }}
                    </span>
                @endforeach
            </div>

            {{-- Letras ya usadas --}}
            @if(count($letras_usadas) > 0)
                <p class="text-center text-muted mb-2" style="font-size:.82rem; letter-spacing:1px">
                    LETRAS USADAS:
                    @foreach($letras_usadas as $lu)
                        <span class="fw-bold text-uppercase {{ str_contains(implode('', $casillas), $lu) ? 'text-success' : 'text-danger' }}">
                            {{ $lu }}
                        </span>
                    @endforeach
                </p>
            @endif

            {{-- Teclado --}}
            <div class="d-flex flex-wrap gap-1 justify-content-center mb-4">
                @foreach($abecedario as $letra)
                    @php
                        $usada    = in_array($letra, $letras_usadas);
                        $acierto  = $usada && !in_array($letra, array_fill(0, count($letras_usadas), ''))
                                    && collect(str_split(session('ahorcado.palabra', '')))->contains($letra);
                        // Determinar color del botón
                        $btnClass = 'btn-outline-secondary';
                        if ($usada) {
                            $enPalabra = str_contains(session('ahorcado.palabra', ''), $letra);
                            $btnClass  = $enPalabra ? 'btn-success' : 'btn-danger';
                        }
                        $disabled = $usada || $ganado || $perdido;
                    @endphp

                    <form action="{{ route('ahorcado.letra') }}" method="POST" class="m-0">
                        @csrf
                        <input type="hidden" name="letra" value="{{ $letra }}">
                        <button type="submit"
                                class="btn btn-letra {{ $btnClass }}"
                                {{ $disabled ? 'disabled' : '' }}>
                            {{ mb_strtoupper($letra) }}
                        </button>
                    </form>
                @endforeach
            </div>

            {{-- Botón nueva partida --}}
            <div class="text-center">
                <form action="{{ route('ahorcado.nueva') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary px-4">
                        {{ $ganado || $perdido ? '🔄 Jugar de nuevo' : '🔄 Nueva palabra' }}
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

</body>
</html>