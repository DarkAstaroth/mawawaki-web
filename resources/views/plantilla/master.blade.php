<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('complemento.estilos')
    <title>Inicio</title>
    <link rel="icon" type="image/png" href="assets/media/logos/scallia-min.png">

    {{-- @routes
    @vite(['resources/js/app.js', 'resources/js/Pages/configuraciones/roles/index.vue']) --}}
    {{-- @inertiaHead --}}


</head>

<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true"
    data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
    data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true"
    data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">


    <!--begin::App-->
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <!--begin::Page-->
        <div class="app-page  flex-column flex-column-fluid " id="kt_app_page">
            <!--layout-partial:layout/partials/_header.html-->
            @include('plantilla.secciones.header')
            <!--begin::Wrapper-->
            <div class="app-wrapper  flex-column flex-row-fluid " id="kt_app_wrapper">
                <!--layout-partial:layout/partials/_sidebar.html-->
                @include('plantilla.secciones.sidebar')
                <!--begin::Main-->
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <!--begin::Content wrapper-->
                    <div class="d-flex flex-column flex-column-fluid">
                        <!--layout-partial:layout/partials/_toolbar.html-->
                        @include('plantilla.secciones.cuerpo.toolbar')
                        <!--layout-partial:layout/partials/_content.html-->
                        @include('plantilla.secciones.cuerpo.contenido')
                    </div>
                    <!--end::Content wrapper-->
                    <!--layout-partial:layout/partials/_footer.html-->
                </div>
                <!--end:::Main-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::App-->
    <!--layout-partial:partials/_drawers.html-->
    @vite(['resources/js/app.js'])
    {{-- <script src="{{ asset('build/assets/app-9a281880.js') }}" defer></script> --}}
 
    @include('complemento.tema')
    @include('complemento.scripts')

</body>

</html>
