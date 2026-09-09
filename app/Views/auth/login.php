<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Iniciar Sesión | Sistema de Facturación</title>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Bootstrap -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <!-- AdminLTE -->
    <link rel="stylesheet"
          href="<?= base_url('assets/adminlte/dist/css/adminlte.min.css') ?>">

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;

            font-family: Arial, Helvetica, sans-serif;

            background:
                linear-gradient(
                    rgba(2, 6, 23, 0.45),
                    rgba(15, 23, 42, 0.70)
                ),
                url("<?= base_url('assets/img/fondo.jpg') ?>");

            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;

            overflow: hidden;
        }


        /* =========================================
           CONTENEDOR
           ========================================= */

        .login-container {
            min-height: 100vh;

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 25px;

            position: relative;
        }


        /* =========================================
           CÍRCULOS DECORATIVOS
           ========================================= */

        .circle {
            position: absolute;

            border-radius: 50%;

            filter: blur(1px);

            opacity: 0.75;

            pointer-events: none;
        }

        .circle-one {
            width: 220px;
            height: 220px;

            background: #7c3aed;

            top: -80px;
            left: -70px;

            box-shadow:
                0 0 80px #7c3aed;
        }

        .circle-two {
            width: 180px;
            height: 180px;

            background: #06b6d4;

            bottom: -70px;
            right: -50px;

            box-shadow:
                0 0 80px #06b6d4;
        }


        /* =========================================
           TARJETA PRINCIPAL
           ========================================= */

        .login-card {
            width: 100%;

            max-width: 430px;

            position: relative;

            z-index: 2;

            padding: 35px;

            border-radius: 28px;

            background: rgba(15, 23, 42, 0.78);

            backdrop-filter: blur(20px);

            -webkit-backdrop-filter: blur(20px);

            border: 1px solid rgba(255,255,255,0.15);

            box-shadow:
                0 30px 80px rgba(0,0,0,0.55),
                0 0 35px rgba(124,58,237,0.15);

            animation: entrada 0.8s ease;
        }


        @keyframes entrada {

            from {
                opacity: 0;

                transform:
                    translateY(30px)
                    scale(0.96);
            }

            to {
                opacity: 1;

                transform:
                    translateY(0)
                    scale(1);
            }
        }


        /* =========================================
           LÍNEA SUPERIOR
           ========================================= */

        .top-line {
            height: 4px;

            width: 100%;

            border-radius: 20px;

            background: linear-gradient(
                90deg,
                #06b6d4,
                #3b82f6,
                #8b5cf6,
                #ec4899
            );

            margin-bottom: 30px;
        }


        /* =========================================
           ICONO
           ========================================= */

        .logo {
            width: 82px;
            height: 82px;

            margin: auto;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 22px;

            background: linear-gradient(
                135deg,
                #2563eb,
                #7c3aed
            );

            color: white;

            font-size: 38px;

            box-shadow:
                0 15px 35px rgba(59,130,246,0.35);

            transform: rotate(-3deg);

            transition: 0.3s;
        }


        .logo:hover {
            transform:
                rotate(0deg)
                scale(1.05);
        }


        /* =========================================
           TÍTULO
           ========================================= */

        .title {
            color: white;

            font-size: 28px;

            font-weight: 800;

            margin-top: 20px;

            margin-bottom: 5px;
        }


        .subtitle {
            color: #94a3b8;

            font-size: 14px;

            margin-bottom: 30px;
        }


        /* =========================================
           CAMPOS
           ========================================= */

        .field {
            margin-bottom: 20px;
        }


        .field label {
            display: block;

            color: #cbd5e1;

            font-size: 12px;

            font-weight: 700;

            letter-spacing: 1px;

            margin-bottom: 8px;
        }


        .input-box {
            display: flex;

            align-items: center;

            height: 54px;

            border-radius: 14px;

            background: rgba(255,255,255,0.07);

            border: 1px solid rgba(255,255,255,0.12);

            transition: 0.25s;
        }


        .input-box i {
            color: #60a5fa;

            font-size: 19px;

            width: 50px;

            text-align: center;
        }


        .input-box input {
            width: 100%;

            height: 100%;

            border: none;

            outline: none;

            background: transparent;

            color: white;

            padding-right: 15px;

            font-size: 15px;
        }


        .input-box input::placeholder {
            color: #64748b;
        }


        .input-box:focus-within {
            border-color: #8b5cf6;

            background: rgba(255,255,255,0.10);

            box-shadow:
                0 0 0 4px rgba(139,92,246,0.12);
        }


        .input-box:focus-within i {
            color: #a78bfa;
        }


        /* =========================================
           BOTÓN
           ========================================= */

        .btn-login {
            width: 100%;

            height: 55px;

            border: none;

            border-radius: 14px;

            color: white;

            font-size: 15px;

            font-weight: 700;

            background: linear-gradient(
                90deg,
                #2563eb,
                #7c3aed,
                #db2777
            );

            box-shadow:
                0 10px 25px rgba(124,58,237,0.30);

            transition: 0.3s;

            margin-top: 8px;
        }


        .btn-login:hover {
            transform: translateY(-2px);

            box-shadow:
                0 15px 35px rgba(124,58,237,0.45);
        }


        .btn-login i {
            margin-right: 8px;
        }


        /* =========================================
           ALERTA
           ========================================= */

        .custom-alert {
            background: rgba(239,68,68,0.15);

            color: #fecaca;

            border: 1px solid rgba(239,68,68,0.30);

            border-radius: 12px;

            font-size: 14px;
        }


        /* =========================================
           FOOTER
           ========================================= */

        .footer {
            text-align: center;

            color: #64748b;

            font-size: 12px;

            margin-top: 25px;

            padding-top: 20px;

            border-top: 1px solid rgba(255,255,255,0.08);
        }


        .footer strong {
            color: #94a3b8;
        }


        /* =========================================
           RESPONSIVE
           ========================================= */

        @media (max-width: 500px) {

            body {
                overflow: auto;
            }

            .login-container {
                padding: 15px;
            }

            .login-card {
                padding: 25px 20px;
            }

            .title {
                font-size: 24px;
            }
        }

    </style>
</head>


<body>


<div class="login-container">

    <!-- Decoración -->

    <div class="circle circle-one"></div>

    <div class="circle circle-two"></div>


    <!-- LOGIN -->

    <div class="login-card">

        <div class="top-line"></div>


        <!-- Logo -->

        <div class="text-center">

            <div class="logo">

                <i class="bi bi-receipt-cutoff"></i>

            </div>


            <h1 class="title">
                Facturación App
            </h1>


            <p class="subtitle">
                Sistema de gestión y facturación
            </p>

        </div>


        <!-- MENSAJE DE ERROR -->

        <?php if (session()->getFlashdata('error')): ?>

            <div class="alert custom-alert mb-4">

                <i class="bi bi-exclamation-triangle-fill me-2"></i>

                <?= session()->getFlashdata('error') ?>

            </div>

        <?php endif; ?>


        <!-- FORMULARIO -->

        <form action="<?= base_url('login/authenticate') ?>"
              method="post"
              autocomplete="off">

            <?= csrf_field() ?>


            <!-- USUARIO -->

            <div class="field">

                <label for="username">
                    USUARIO
                </label>

                <div class="input-box">

                    <i class="bi bi-person-fill"></i>

                    <input
                        type="text"
                        name="username"
                        id="username"
                        placeholder="Ingresa tu usuario"
                        required
                        autofocus
                    >

                </div>

            </div>


            <!-- CONTRASEÑA -->

            <div class="field">

                <label for="password">
                    CONTRASEÑA
                </label>

                <div class="input-box">

                    <i class="bi bi-lock-fill"></i>

                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="Ingresa tu contraseña"
                        required
                    >

                </div>

            </div>


            <!-- BOTÓN -->

            <button
                type="submit"
                class="btn-login">

                <i class="bi bi-box-arrow-in-right"></i>

                Iniciar Sesión

            </button>

        </form>


        <!-- FOOTER -->

        <div class="footer">

            © <?= date('Y') ?>

            <strong>Sistema de Facturación Joham Company</strong>

            <br>

            Todos los derechos reservados.

        </div>

    </div>

</div>


<!-- Bootstrap -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


<!-- AdminLTE -->

<script src="<?= base_url('assets/adminlte/dist/js/adminlte.min.js') ?>">
</script>


</body>
</html>


