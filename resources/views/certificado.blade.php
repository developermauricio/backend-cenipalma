<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Descargar certificado</title>
    <link rel="stylesheet" href="{{ asset('/css/bootstrap.css') }}">

<body>
    <div class="container pt-3 d-flex flex-column min-vh-100" id="app">
        <header>
            <div class="d-flex flex-column flex-md-row align-items-center pb-3 mb-4 border-bottom">
                <a href="#" class="d-flex align-items-center text-dark text-decoration-none">
                    <img style="width: 12rem"
                      src="https://reuniontecnicanacional.com/wp-content/uploads/2021/09/LOGO-RTN-02.png" alt="logo">
                </a>
            </div>
        </header>
        <main class="pb-4 main flex-grow-1">
            <div class="row">
                <div class="col">
                    <certificado-component></certificado-component>
                </div>
            </div>
        </main>
        <footer class="p-4 rounded-top bg-primary text-white">
            <div class="container d-flex justify-content-center align-items-center">
                <small class="text-white">
                    <a class="text-white" href="https://reuniontecnicanacional.com/">
                        <strong>Virtual Cenipalma</strong>
                    </a>
                    <i class="fa fa-copyright"></i>
                    2021 POWERED BY
                    <a class="text-white" href="http://www.creategicalatina.com">
                        <strong>Creategicalatina</strong>
                    </a>.
                </small>
            </div>
        </footer>
    </div>
    <script src="{{ asset('/js/certificado.js') }}"></script>
</body>

</html>