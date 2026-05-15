<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            min-height: 100vh;
        }

        .calc-card {
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
            max-width: 540px;
        }

        /* Displays */
        .display-box {
            background: #e9ecef;
            border: 2px solid #ced4da;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            font-size: 1.4rem;
            text-align: right;
            padding: 10px 14px;
            min-width: 0;
        }

        .display-box.activo {
            border-color: #0d6efd;
            background: #eef3ff;
        }

        .display-resultado {
            background: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            font-size: 1.4rem;
            text-align: right;
            padding: 10px 14px;
            min-width: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .display-resultado.error {
            background: #f8d7da;
            border-color: #dc3545;
            color: #dc3545;
            font-size: 0.95rem;
        }

        /* Botones */
        .btn-calc {
            font-size: 1.1rem;
            font-weight: 600;
            padding: 14px 0;
            border-radius: 6px;
            width: 100%;
        }

        .btn-op-active {
            box-shadow: 0 0 0 3px #0d6efd55;
        }

        .display-op {
            width: 48px;
            height: 48px;
            border: 2px dashed #ced4da;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            font-weight: 700;
            color: #6c757d;
            background: #f8f9fa;
            transition: all 0.15s;
        }

        .display-op--activa {
            border: 2px solid #0d6efd;
            background: #eef3ff;
            color: #0d6efd;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center py-5">

<div class="card calc-card p-4 w-100 mx-3">
    <h5 class="text-center text-secondary mb-4 fw-normal" style="letter-spacing:3px; text-transform:uppercase; font-size:.85rem">Calculadora</h5>

    {{-- ═══ DISPLAYS ═══ --}}
    <div class="row g-2 align-items-center mb-1">

        {{-- Nº1 con botón de activar --}}
        <div class="col">
            <div class="d-flex align-items-center gap-2">
                <form action="{{ route('activar') }}" method="POST" class="m-0">
                    @csrf
                    <input type="hidden" name="campo" value="n1">
                    <!--<button type="submit" class="btn btn-sm {{ $activo === 'n1' ? 'btn-primary' : 'btn-outline-secondary' }} py-0 px-2" style="font-size:.75rem">Nº1</button>-->
                </form>
                <div class="display-box flex-grow-1 {{ $activo === 'n1' ? 'activo' : '' }}">{{ $n1 }}</div>
            </div>
        </div>

        {{-- Caja de operación --}}
        @php
            $simbolos = [
                'suma'           => '+',
                'resta'          => '−',
                'multiplicacion' => '×',
                'division'       => '/',
                'modulo'         => '%',
            ];
        @endphp
        <div class="col-auto">
            <div class="display-op {{ $operacion ? 'display-op--activa' : '' }}">
                {{ $operacion ? ($simbolos[$operacion] ?? '') : '' }}
            </div>
        </div>

        {{-- Nº2 con botón de activar --}}
        <div class="col">
            <div class="d-flex align-items-center gap-2">
                <form action="{{ route('activar') }}" method="POST" class="m-0">
                    @csrf
                    <input type="hidden" name="campo" value="n2">
                    <!--<button type="submit" class="btn btn-sm {{ $activo === 'n2' ? 'btn-primary' : 'btn-outline-secondary' }} py-0 px-2" style="font-size:.75rem">Nº2</button>-->
                </form>
                <div class="display-box flex-grow-1 {{ $activo === 'n2' ? 'activo' : '' }}">{{ $n2 }}</div>
            </div>
        </div>

        <div class="col-auto text-muted fw-bold">=</div>

        {{-- Resultado --}}
        <div class="col">
            <div class="display-resultado {{ $error ? 'error' : '' }}">
                @if($error)
                    {{ $error }}
                @elseif($resultado !== null)
                    {{ $resultado }}
                @else
                    —
                @endif
            </div>
        </div>
    </div>

    <div class="mb-3"></div>

    {{-- ═══ TECLADO ═══ --}}
    {{-- Macro para botón dígito --}}
    @php
        function btnDigito(string $d, string $route, string $csrf): string {
            return '<form action="'.$route.'" method="POST" class="m-0">
                        <input type="hidden" name="_token" value="'.$csrf.'">
                        <input type="hidden" name="digito" value="'.$d.'">
                        <button type="submit" class="btn btn-light btn-calc border">'.$d.'</button>
                    </form>';
        }
    @endphp

    <div class="row g-2">

        {{-- Fila 1: 7 8 9 % / × --}}
        <div class="col-2">
            <form action="{{ route('digito') }}" method="POST">@csrf<input type="hidden" name="digito" value="7"><button type="submit" class="btn btn-light btn-calc border">7</button></form>
        </div>
        <div class="col-2">
            <form action="{{ route('digito') }}" method="POST">@csrf<input type="hidden" name="digito" value="8"><button type="submit" class="btn btn-light btn-calc border">8</button></form>
        </div>
        <div class="col-2">
            <form action="{{ route('digito') }}" method="POST">@csrf<input type="hidden" name="digito" value="9"><button type="submit" class="btn btn-light btn-calc border">9</button></form>
        </div>
        <div class="col-2">
            <form action="{{ route('operacion') }}" method="POST">@csrf<input type="hidden" name="op" value="modulo"><button type="submit" class="btn btn-outline-primary btn-calc {{ $operacion === 'modulo' ? 'btn-op-active' : '' }}">%</button></form>
        </div>
        <div class="col-2">
            <form action="{{ route('operacion') }}" method="POST">@csrf<input type="hidden" name="op" value="division"><button type="submit" class="btn btn-outline-primary btn-calc {{ $operacion === 'division' ? 'btn-op-active' : '' }}">/</button></form>
        </div>
        <div class="col-2">
            <form action="{{ route('operacion') }}" method="POST">@csrf<input type="hidden" name="op" value="multiplicacion"><button type="submit" class="btn btn-outline-primary btn-calc {{ $operacion === 'multiplicacion' ? 'btn-op-active' : '' }}">×</button></form>
        </div>

        {{-- Fila 2: 4 5 6 - + C --}}
        <div class="col-2">
            <form action="{{ route('digito') }}" method="POST">@csrf<input type="hidden" name="digito" value="4"><button type="submit" class="btn btn-light btn-calc border">4</button></form>
        </div>
        <div class="col-2">
            <form action="{{ route('digito') }}" method="POST">@csrf<input type="hidden" name="digito" value="5"><button type="submit" class="btn btn-light btn-calc border">5</button></form>
        </div>
        <div class="col-2">
            <form action="{{ route('digito') }}" method="POST">@csrf<input type="hidden" name="digito" value="6"><button type="submit" class="btn btn-light btn-calc border">6</button></form>
        </div>
        <div class="col-2">
            <form action="{{ route('operacion') }}" method="POST">@csrf<input type="hidden" name="op" value="resta"><button type="submit" class="btn btn-outline-primary btn-calc {{ $operacion === 'resta' ? 'btn-op-active' : '' }}">−</button></form>
        </div>
        <div class="col-2">
            <form action="{{ route('operacion') }}" method="POST">@csrf<input type="hidden" name="op" value="suma"><button type="submit" class="btn btn-outline-primary btn-calc {{ $operacion === 'suma' ? 'btn-op-active' : '' }}">+</button></form>
        </div>
        <div class="col-2">
            <form action="{{ route('limpiar') }}" method="POST">@csrf<button type="submit" class="btn btn-danger btn-calc">C</button></form>
        </div>

        {{-- Fila 3: 1 2 3 0 √n x² --}}
        <div class="col-2">
            <form action="{{ route('digito') }}" method="POST">@csrf<input type="hidden" name="digito" value="1"><button type="submit" class="btn btn-light btn-calc border">1</button></form>
        </div>
        <div class="col-2">
            <form action="{{ route('digito') }}" method="POST">@csrf<input type="hidden" name="digito" value="2"><button type="submit" class="btn btn-light btn-calc border">2</button></form>
        </div>
        <div class="col-2">
            <form action="{{ route('digito') }}" method="POST">@csrf<input type="hidden" name="digito" value="3"><button type="submit" class="btn btn-light btn-calc border">3</button></form>
        </div>
        <div class="col-2">
            <form action="{{ route('digito') }}" method="POST">@csrf<input type="hidden" name="digito" value="0"><button type="submit" class="btn btn-light btn-calc border">0</button></form>
        </div>
        <div class="col-2">
            <form action="{{ route('unaria') }}" method="POST">@csrf<input type="hidden" name="op" value="raiz"><button type="submit" class="btn btn-outline-success btn-calc">√n</button></form>
        </div>
        <div class="col-2">
            <form action="{{ route('unaria') }}" method="POST">@csrf<input type="hidden" name="op" value="cuadrado"><button type="submit" class="btn btn-outline-success btn-calc">x²</button></form>
        </div>

        {{-- Fila 4: . ← = (span) --}}
        <div class="col-2">
            <form action="{{ route('digito') }}" method="POST">@csrf<input type="hidden" name="digito" value="."><button type="submit" class="btn btn-light btn-calc border">.</button></form>
        </div>
        <div class="col-2">
            <form action="{{ route('borrar') }}" method="POST">@csrf<button type="submit" class="btn btn-outline-secondary btn-calc">⌫</button></form>
        </div>
        <div class="col-8">
            <form action="{{ route('calcular') }}" method="POST">@csrf<button type="submit" class="btn btn-primary btn-calc w-100">=</button></form>
        </div>

    </div>
</div>

</body>
</html>