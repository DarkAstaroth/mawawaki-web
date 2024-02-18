<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('complemento.estilos')
    <title>EQT - Centro Integral de Terapias Equinas Mawqawaki Sarañani</title>
    <link rel="icon" type="image/png" href="assets/media/logos/scallia-min.png">

</head>

<body id="kt_body" data-bs-spy="scroll" data-bs-target="#kt_landing_menu" class="bg-white position-relative app-blank">

    <script>
        var defaultThemeMode = "light";
        var themeMode;

        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }

            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }

            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>


    <!--begin::Root-->
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        @include('layouts.landing.secciones.header')
        {{-- @include('layouts.landing.secciones.info') --}}
        {{-- @include('layouts.landing.secciones.estadisticas')
        @include('layouts.landing.secciones.equipo')
        @include('layouts.landing.secciones.proyectos')
        @include('layouts.landing.secciones.precios')
        @include('layouts.landing.secciones.testimonios')
        @include('layouts.landing.secciones.footer') --}}

    </div>
    <!--end::Root-->

    <!--begin::Scrolltop-->
    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <i class="ki-duotone ki-arrow-up"><span class="path1"></span><span class="path2"></span></i>
    </div>
    <!--end::Scrolltop-->


    @vite(['resources/js/app.js'])
    @include('complemento.tema')
    @include('complemento.scripts')

</body>
<!--end::Body-->

</html>
