<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 | Not Found</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500&display=swap" rel="stylesheet" />

    <style>
        /* Reseteo global, tipografía y centrado absoluto en un solo bloque */
        body {
            margin: 0;
            box-sizing: border-box;
            background-color: #1a202c;
            color: #a0aec0;
            font-family: 'Figtree', sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1.5rem;
        }

        /* Contenedor principal de los elementos */
        .container {
            display: flex;
            align-items: center;
            max-width: 32rem;
        }

        /* Número 404 (Izquierda) */
        .error-code {
            font-size: 1.5rem;
            font-weight: 500;
            color: #cbd5e0;
            padding-right: 1.5rem;
            letter-spacing: .05em;
        }

        /* Bloque de textos (Derecha) + Línea divisoria vertical */
        .error-message {
            padding-left: 1.5rem;
            border-left: 2px solid #4a5568;
        }

        /* Formato para el H1 (Título) y P (Mensaje de construcción) */
        .error-message h1 {
            font-size: 1.125rem;
            font-weight: 400;
            text-transform: uppercase;
            letter-spacing: .1em;
            margin-bottom: 0.25rem;
        }

        .error-message p {
            font-size: 0.95rem;
            color: #718096;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="error-code">404</div>
        <div class="error-message">
            <h1>Not Found</h1>
            <p>The page is in construction.</p>
        </div>
    </div>

</body>
</html>