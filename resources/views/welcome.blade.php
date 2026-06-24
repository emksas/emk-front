<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>EMK</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <style>
        /* --- Variables de Color y Reset --- */
        :root {
            --primary-blue: #16465B;
            --bg-cream: #f0e7cf;
            --white: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Figtree', sans-serif;
        }

        body {
            background-color: var(--bg-cream);
            color: var(--primary-blue);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* --- Navigation Bar (Navbar) --- */
        .navbar {
            background-color: var(--primary-blue);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .brand-logo {
            color: var(--bg-cream);
            font-size: 1.5rem;
            font-weight: 600;
            text-decoration: none;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        /* Botones de Login y Registro */
        .btn {
            text-decoration: none;
            padding: 0.6rem 1.2rem;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-login {
            color: var(--bg-cream);
            border: 1px solid var(--bg-cream);
        }

        .btn-login:hover {
            background-color: var(--bg-cream);
            color: var(--primary-blue);
        }

        .btn-register {
            background-color: var(--bg-cream);
            color: var(--primary-blue);
        }

        .btn-register:hover {
            background-color: var(--white);
            transform: translateY(-2px);
        }

        /* Enlace de WhatsApp */
        .whatsapp-link {
            color: var(--bg-cream);
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.95rem;
            transition: color 0.3s;
        }

        .whatsapp-link:hover {
            color: var(--white);
        }

        /* --- Cuerpo de la página (Main Content) --- */
        .main-container {
            max-width: 1200px;
            margin: 3rem auto;
            padding: 0 1.5rem;
            width: 100%;
            flex-grow: 1;
        }

        /* Grid de 6 columnas para poder centrar la tercera carta 
           en orden de "Pirámide Invertida"
        */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 2rem;
        }

        /* Estilo base de las cartas con transición para el hover */
        .card {
            background-color: var(--white);
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: 0 4px 6px -1px rgba(22, 70, 91, 0.05), 0 2px 4px -1px rgba(22, 70, 91, 0.03);
            border: 1px solid rgba(22, 70, 91, 0.08);
            transition: transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1),
                box-shadow 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            text-decoration: none;
            /* Elimina cualquier subrayado extraño */
            display: block;
            /* Hace que todo el bloque de la tarjeta sea clickeable */
            cursor: pointer;
        }

        /* --- EFECTO HOVER (ELEVACIÓN) --- */
        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(22, 70, 91, 0.15), 0 10px 10px -5px rgba(22, 70, 91, 0.08);
        }

        /* Posicionamiento de las cartas en el escritorio */
        .card-1 {
            grid-column: span 3;
            /* Ocupa las primeras 3 columnas (Mitad izquierda) */
        }

        .card-2 {
            grid-column: span 3;
            /* Ocupa las siguientes 3 columnas (Mitad derecha) */
        }

        .card-3 {
            grid-column: 2 / span 4;
            /* Se salta la primera columna y se expande por 4, quedando perfectamente centrada */
        }

        /* Carta Izquierda (Texto) */
        .card-text h1 {
            font-size: 2.2rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .card-text p {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #335c70;
        }

        /* --- RESPONSIVE / ADAPTABILIDAD (Móviles y Tablets verticales) --- */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
                padding: 1rem;
            }

            .nav-actions {
                flex-direction: column;
                width: 100%;
                gap: 0.8rem;
            }

            .btn,
            .whatsapp-link {
                width: 100%;
                text-align: center;
                justify-content: center;
            }

            /* En móviles se rompe la pirámide para que se apilen una sobre otra al 100% */
            .cards-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .card-1,
            .card-2,
            .card-3 {
                grid-column: span 1;
            }

            .main-container {
                margin: 1.5rem auto;
            }
        }

        /* --- Pie de Página (Footer) --- */
        .footer {
            background-color: var(--primary-blue);
            color: var(--white);
            padding: 3rem 2rem 1.5rem 2rem;
            margin-top: auto;
            /* Empuja el footer al fondo si hay poco contenido */
            border-top: 4px solid #1a526a;
            /* Un ligero relieve */
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2.5rem;
            border-bottom: 1px solid rgba(249, 234, 188, 0.2);
            /* Línea divisoria sutil */
            padding-bottom: 2rem;
        }

        .footer-section h3 {
            color: var(--bg-cream);
            /* Usamos el color crema para destacar títulos */
            font-size: 1.2rem;
            margin-bottom: 1.2rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .footer-section p {
            font-size: 0.95rem;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.85);
        }

        /* Enlaces dentro del Footer */
        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.8rem;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            font-size: 0.95rem;
            transition: color 0.2s ease, padding-left 0.2s ease;
        }

        .footer-links a:hover {
            color: var(--bg-cream);
            padding-left: 4px;
            /* Efecto sutil al pasar el cursor */
        }

        /* Información de Contacto */
        .contact-info li {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.8rem;
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.85);
        }

        /* Barra Inferior de Derechos y Legal */
        .footer-bottom {
            max-width: 1200px;
            margin: 1.5rem auto 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.6);
        }

        .legal-links {
            display: flex;
            gap: 1.5rem;
        }

        .legal-links a {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            transition: color 0.2s;
        }

        .legal-links a:hover {
            color: var(--bg-cream);
        }

        /* --- RESPONSIVE EN FOOTER --- */
        @media (max-width: 768px) {
            .footer-container {
                grid-template-columns: 1fr;
                /* Se apila en una sola columna en móviles */
                gap: 2rem;
                text-align: center;
            }

            .contact-info li {
                justify-content: center;
            }

            .footer-bottom {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .legal-links {
                justify-content: center;
                flex-wrap: wrap;
            }
        }
    </style>
</head>

<body>

    <header class="navbar">
        <a href="#" class="brand-logo">EMK</a>

        <div class="nav-actions">
            <a href="https://wa.me/573153422939" target="_blank" class="whatsapp-link">
                💬 +57 315 3422939
            </a>
            <a href="{{ route('login') }}" class="btn btn-login">Login</a>
            <a href="{{ route('register') }}" class="btn btn-register">Create new user</a>
        </div>
    </header>

    <main class="main-container">
        <div class="cards-grid">

            <a href="url('404')" class="card card-text card-1">
                <h1>Contabilidad y Servicios Financieros </h1>
                <p>...</p>
            </a>

            <a href="url('404')" class="card card-text card-2">
                <h1>Suministros y Servicios Industriales </h1>
                <p>...</p>
            </a>

            <a href="url('404')" class="card card-text card-3">
                <h1>Artesania y Manufactura Textil</h1>
                <p>...</p>
            </a>

        </div>
    </main>

    <footer class="footer">
        <div class="footer-container">

            <div class="footer-section">
                <h3>EMK Sistema Contable</h3>
                <p>Solución integral para la gestión financiera, auditoría y control de suministros industriales.
                    Optimizado bajo normativas vigentes y estándares internacionales de contabilidad.</p>
            </div>

            <div class="footer-section">
                <h3>Módulos del Sistema</h3>
                <ul class="footer-links">
                    <li><a href="url('404')">📊 Facturación Electrónica</a></li>
                    <li><a href="url('404')">📦 Control de Inventarios</a></li>
                    <li><a href="url('404')">📈 Reportes NIIF / Balances</a></li>
                    <li><a href="url('404')">⚙️ Configuración Fiscal</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Soporte y Contacto</h3>
                <ul class="contact-info">
                    <li>📧 soporte@emk.com.co</li>
                    <li>📞 +57 (601) 123-4567</li>
                    <li>🕒 Lun - Vie: 8:00 AM - 6:00 PM</li>
                    <li>📍 Bogotá, Colombia</li>
                </ul>
            </div>

        </div>

        <div class="footer-bottom">
            <p>&copy; 2026 EMK. Todos los derechos reservados.</p>
            <div class="legal-links">
                <a href="#">Términos de Servicio</a>
                <a href="#">Política de Privacidad</a>
                <a href="#">Tratamiento de Datos (Habeas Data)</a>
            </div>
        </div>
    </footer>

</body>

</html>