<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ahorcado — Tema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; min-height: 100vh; }
        .tema-card {
            max-width: 480px;
            border-radius: 14px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.10);
        }
        .btn-tema {
            border-radius: 20px;
            font-size: .82rem;
            padding: 4px 14px;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center py-5">

<div class="card tema-card p-4 p-md-5 w-100 mx-3">

    <h5 class="text-center text-secondary fw-normal mb-1"
        style="letter-spacing:3px; text-transform:uppercase; font-size:.8rem">
        El Ahorcado
    </h5>
    <h2 class="text-center fw-bold mb-1" style="font-size:1.5rem">¿Sobre qué tema?</h2>
    <p class="text-center text-muted mb-4" style="font-size:.88rem">
        Elige un tema y la IA generará una palabra para adivinar
    </p>

    @if($errors->any())
        <div class="alert alert-danger py-2 mb-3" style="font-size:.88rem">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('ahorcado.setup') }}" method="POST">
        @csrf
        <div class="mb-3">
            <input
                type="text"
                name="tema"
                class="form-control form-control-lg text-center"
                placeholder="Ej: animales, cine, deportes..."
                value="{{ old('tema') }}"
                autofocus
                required
            >
        </div>

        {{-- Temas de acceso rápido --}}
        <div class="d-flex flex-wrap gap-2 justify-content-center mb-4">
            @foreach(['Animales', 'Cine', 'Deportes', 'Historia', 'Tecnología', 'Cocina', 'Música', 'Geografía'] as $t)
                <button type="submit" name="tema" value="{{ $t }}" class="btn btn-outline-primary btn-tema">
                    {{ $t }}
                </button>
            @endforeach
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-success btn-lg">
                🎮 Empezar partida
            </button>
        </div>
    </form>

    <div class="text-center mt-4">
        <a href="{{ route('inicio') }}" class="text-muted" style="font-size:.82rem">← Volver al inicio</a>
    </div>
</div>

</body>
</html>
