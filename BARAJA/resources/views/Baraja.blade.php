<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mayor o Menor – Baraja Española</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --gold:    #c9a84c;
            --gold-lt: #e8c96a;
            --red:     #b5292a;
            --green:   #2a7a3b;
            --dark:    #1a1209;
            --felt:    #1e4a2d;
            --card-bg: #fdf6e3;
        }

        body {
            min-height: 100vh;
            background-color: var(--felt);
            background-image:
                radial-gradient(ellipse at 50% 0%, #2d6b40 0%, transparent 70%),
                repeating-linear-gradient(
                    45deg,
                    rgba(0,0,0,0.03) 0px,
                    rgba(0,0,0,0.03) 1px,
                    transparent 1px,
                    transparent 8px
                );
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: 'Lato', sans-serif;
            padding: 1.5rem;
            gap: 1.5rem;
        }

        /* ── Título ── */
        h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.6rem, 4vw, 2.4rem);
            color: var(--gold);
            text-shadow: 0 2px 12px rgba(0,0,0,0.6);
            letter-spacing: .08em;
            text-align: center;
        }

        /* ── Contador de aciertos ── */
        .streak {
            display: flex;
            gap: .55rem;
            align-items: center;
        }

        .streak-pip {
            width: 18px; height: 18px;
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,.25);
            background: rgba(255,255,255,.1);
            transition: background .3s, border-color .3s, transform .2s;
        }

        .streak-pip.filled {
            background: var(--gold);
            border-color: var(--gold-lt);
            box-shadow: 0 0 8px var(--gold);
            transform: scale(1.15);
        }

        .streak-label {
            font-size: .8rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: rgba(255,255,255,.55);
            margin-left: .3rem;
        }

        /* ── Área de juego ── */
        .partida-area {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        /* ── Botones de mayor/menor ── */
        .btn-intento {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .45rem;
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
            font-family: 'Lato', sans-serif;
        }

        .btn-intento .arrow-circle {
            width: 72px; height: 72px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            border: 3px solid;
            transition: transform .18s, box-shadow .18s, background .18s;
        }

        .btn-intento.menor .arrow-circle {
            color: var(--red);
            border-color: var(--red);
            background: rgba(181,41,42,.08);
        }

        .btn-intento.mayor .arrow-circle {
            color: var(--green);
            border-color: var(--green);
            background: rgba(42,122,59,.08);
        }

        .btn-intento:hover .arrow-circle,
        .btn-intento:focus .arrow-circle {
            transform: scale(1.12);
            box-shadow: 0 0 20px currentColor;
        }

        .btn-intento .btn-label {
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
        }

        .btn-intento.menor .btn-label { color: var(--red); }
        .btn-intento.mayor .btn-label { color: var(--green); }

        /* ── Carta ── */
        .card-wrapper {
            position: relative;
            perspective: 900px;
        }

        .card {
            width: 180px;
            border-radius: 14px;
            background: var(--card-bg);
            box-shadow:
                0 4px 8px rgba(0,0,0,.35),
                0 12px 40px rgba(0,0,0,.45),
                inset 0 0 0 3px rgba(201,168,76,.4);
            overflow: hidden;
            animation: flip-in .4s ease;
        }

        @keyframes flip-in {
            from { transform: rotateY(-70deg) scale(.85); opacity: 0; }
            to   { transform: rotateY(0deg)  scale(1);   opacity: 1; }
        }

        .card img {
            width: 100%;
            display: block;
        }

        /* ── Banner de resultado ── */
        .result-banner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border-radius: 50%;
            width: 80px; height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.4rem;
            animation: pop .35s cubic-bezier(.34,1.56,.64,1) forwards;
            z-index: 10;
            pointer-events: none;
        }

        .result-banner.acierto {
            background: rgba(42,122,59,.92);
            box-shadow: 0 0 24px rgba(42,122,59,.7);
        }

        .resultado-banner.fallo {
            background: rgba(181,41,42,.92);
            box-shadow: 0 0 24px rgba(181,41,42,.7);
        }

        @keyframes pop {
            0%   { transform: translate(-50%,-50%) scale(0);   opacity: 0; }
            60%  { transform: translate(-50%,-50%) scale(1.15); opacity: 1; }
            100% { transform: translate(-50%,-50%) scale(1);   opacity: 1; }
        }

        /* ── Info debajo de la carta ── */
        .card-info {
            margin-top: .7rem;
            text-align: center;
            font-size: .8rem;
            color: rgba(255,255,255,.45);
            letter-spacing: .06em;
        }

        .card-info strong {
            color: rgba(255,255,255,.75);
        }

        /* ── Modal de victoria ── */
        .overlay {
            position: fixed; inset: 0;
            background: rgba(10,25,15,.82);
            display: flex; align-items: center; justify-content: center;
            z-index: 100;
            animation: fade-in .3s ease;
        }

        @keyframes fade-in {
            from { opacity: 0; } to { opacity: 1; }
        }

        .win-modal {
            background: linear-gradient(160deg, #1e3a28, #0f1f15);
            border: 2px solid var(--gold);
            border-radius: 20px;
            padding: 3rem 2.5rem;
            text-align: center;
            max-width: 380px;
            width: 90%;
            box-shadow: 0 0 60px rgba(201,168,76,.35);
            animation: slide-up .4s cubic-bezier(.34,1.56,.64,1);
        }

        @keyframes slide-up {
            from { transform: translateY(60px); opacity: 0; }
            to   { transform: translateY(0);    opacity: 1; }
        }

        .win-modal .trophy { font-size: 4rem; margin-bottom: .6rem; }

        .win-modal h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: var(--gold);
            margin-bottom: .5rem;
        }

        .win-modal p {
            color: rgba(255,255,255,.65);
            font-size: .95rem;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .btn-restart {
            display: inline-block;
            padding: .85rem 2.2rem;
            background: var(--gold);
            color: var(--dark);
            font-family: 'Lato', sans-serif;
            font-weight: 700;
            font-size: .9rem;
            letter-spacing: .12em;
            text-transform: uppercase;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            text-decoration: none;
            transition: background .2s, transform .15s;
        }

        .btn-restart:hover {
            background: var(--gold-lt);
            transform: scale(1.05);
        }

        /* ── Instrucciones ── */
        .instructions {
            font-size: .75rem;
            color: rgba(255,255,255,.35);
            text-align: center;
            max-width: 320px;
            line-height: 1.6;
            letter-spacing: .03em;
        }

        @media (max-width: 480px) {
            .partida-area { gap: 1rem; }
            .card { width: 140px; }
            .btn-intento .arrow-circle { width: 56px; height: 56px; font-size: 1.7rem; }
        }
    </style>
</head>
<body>

    <h1>Mayor &nbsp;o&nbsp; Menor</h1>

    {{-- Racha de aciertos --}}
    <div class="streak">
        @for ($i = 0; $i < 5; $i++)
            <div class="streak-pip {{ $i < $consecutiva ? 'filled' : '' }}"></div>
        @endfor
        <span class="streak-label">{{ $consecutiva }}/5</span>
    </div>

    {{-- Área de juego --}}
    <div class="partida-area">

        {{-- Botón MENOR (izquierda) --}}
        <form method="POST" action="{{ route('partida.intento') }}">
            @csrf
            <input type="hidden" name="intento" value="menor">
            <button type="submit" class="btn-intento menor">
                <div class="arrow-circle">↓</div>
                <span class="btn-label">Menor</span>
            </button>
        </form>

        {{-- Carta actual --}}
        <div>
            <div class="card-wrapper">
                <div class="card">
                    <img
                        src="{{ asset('images/' . $cartaActual . '.jpg') }}"
                        alt="Carta {{ $cartaActual }} de bastos"
                    >
                </div>

                {{-- Icono de resultado (✓ o ✗) --}}
                @if ($resultado === 'acierto')
                    <div class="result-banner acierto">✓</div>
                @elseif ($resultado === 'fallo')
                    <div class="resultado-banner fallo">✗</div>
                @endif
            </div>

            {{-- Info sobre la jugada anterior --}}
            @if ($cartaAnterior && $intento)
                <p class="card-info">
                    Era el <strong>{{ $cartaAnterior }}</strong>,
                    dijiste <strong>{{ $intento }}</strong>
                    → carta <strong>{{ $cartaActual }}</strong>
                </p>
            @else
                <p class="card-info">¿La siguiente será mayor o menor?</p>
            @endif
        </div>

        {{-- Botón MAYOR (derecha) --}}
        <form method="POST" action="{{ route('partida.intento') }}">
            @csrf
            <input type="hidden" name="intento" value="mayor">
            <button type="submit" class="btn-intento mayor">
                <div class="arrow-circle">↑</div>
                <span class="btn-label">Mayor</span>
            </button>
        </form>

    </div>

    <p class="instructions">
        Consigue <strong style="color:rgba(255,255,255,.6)">5 aciertos seguidos</strong>
        para ganar. Si fallas, la racha vuelve a cero.
        Los empates cuentan como fallo.
    </p>

    {{-- Modal de victoria --}}
    @if ($victoria)
    <div class="overlay">
        <div class="win-modal">
            <div class="trophy">🏆</div>
            <h2>¡Has ganado!</h2>
            <p>Conseguiste 5 aciertos consecutivos.<br>Eres un maestro de la baraja española.</p>
            <form method="POST" action="{{ route('partida.reset') }}">
                @csrf
                <button type="submit" class="btn-restart">Jugar de nuevo</button>
            </form>
        </div>
    </div>
    @endif

</body>
</html>