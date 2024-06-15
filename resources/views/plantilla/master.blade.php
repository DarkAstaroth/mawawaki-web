@php
    $assetsDirectory = public_path('build/assets');
    $cssFiles = [];
    $jsFiles = [];

    if (is_dir($assetsDirectory)) {
        $files = scandir($assetsDirectory);

        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $filePath = 'build/assets/' . $file;
                $extension = pathinfo($file, PATHINFO_EXTENSION);

                if ($extension === 'css') {
                    $cssFiles[] = $file;
                } elseif ($extension === 'js' && strpos($file, 'app') === 0) {
                    $jsFiles[] = $filePath;
                }
            }
        }

        if (!empty($jsFiles)) {
            $firstJs = reset($jsFiles);
        }
    }
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('complemento.estilos')
    <title>Inicio</title>
    <link rel="icon" type="image/png" href="assets/media/logos/scallia-min.png">

    @routes

    @if (!is_dir($assetsDirectory))
        @vite(['resources/js/app.js'])
        @vite('resources/css/app.css')
    @endif

    @if (!empty($cssFiles))
        @foreach ($cssFiles as $cssFile)
            <link rel="stylesheet" href="/build/assets/{{ $cssFile }}">
        @endforeach
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
</head>

<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true"
    data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
    data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true"
    data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">

    <div id="app">
        <!--begin::App-->
        <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
            <!--begin::Page-->
            <div class="app-page flex-column flex-column-fluid " id="kt_app_page">
                <!--layout-partial:layout/partials/_header.html-->
                @include('plantilla.secciones.header')
                <!--begin::Wrapper-->
                <div class="app-wrapper flex-column flex-row-fluid " id="kt_app_wrapper">
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
    </div>
    <!--end::App-->
    <!--layout-partial:partials/_drawers.html-->

    @if (!empty($firstJs))
        <script type="module" src="/{{ $firstJs }}"></script>
    @endif

    @include('complemento.tema')
    @include('complemento.scripts')

    <script type="text/javascript">
        window.Laravel = {
            csrfToken: "{{ csrf_token() }}",
            jsPermissions: {!! auth()->user()?->jsPermissions() !!}
        }
    </script>

    {{-- estilos de componentes vue --}}
    <style>
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Urbanist';
            font-weight: 600
        }

        p {
            font-family: 'Quicksand';
            font-size: 15px;
        }

        button {
            font-family: 'Urbanist';
            font-weight: 600;
            font-size: 15px;
        }

        .dp__input {
            font-family: 'Inter';
            font-weight: 500;
            font-size: 14px;
            height: 100%;
        }

        .multiselect__placeholder {
            font-family: 'Inter';
            font-weight: 500;
            font-size: 14px;
        }

        .multiselect__single {
            font-size: 13px;
        }

        .p-button {
            padding: 0.5rem 1rem;
            font-size: 1.1rem;
            transition: background-color 0.2s, color 0.2s, border-color 0.2s, box-shadow 0.2s, outline-color 0.2s;
            border-radius: 6px;
            outline-color: transparent;
        }

        .p-tabview-title {
            line-height: 1;
            white-space: nowrap;
            color: #101820
        }

        .avatar_usuario {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 50px;
            height: 50px;
            overflow: hidden;
            border-radius: 50%;

        }

        .container_usuario {
            width: 100px;
            height: 100px;
            overflow: hidden;
            margin: 10px;
            position: relative;
            border-radius: 50%;
        }

        .contenido__credencial {
            padding: 10%;

        }

        .credencial {
            width: 100%;
            border-radius: 10px;
            overflow: hidden;
        }

        .fondo_credencial img {
            width: 100%;
            height: 480px;
            object-fit: fill;
        }

        .container_credencial {
            display: flex;
            justify-content: center;
            align-items: center;
            position: absolute;
            top: 10%;
        }

        .container_usuario>.crop {
            position: absolute;
            left: -100%;
            right: -100%;
            top: -100%;
            bottom: -100%;
            margin: auto;
            min-height: 100%;
            min-width: 100%;
        }

        .dp--menu-wrapper {}

        .p-dialog-content {
            overflow-y: visible;
        }

        .dp__input {
            height: 45px;
        }
    </style>

    <style>
        .image-input-placeholder {
            background-image: url('svg/avatars/blank.svg');
        }

        [data-bs-theme="dark"] .image-input-placeholder {
            background-image: url('svg/avatars/blank-dark.svg');
        }
    </style>

</body>

</html>

@if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Upsss..',
            text: '{{ session('error') }}'
        });
    </script>
@endif

@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Operación Exitosa!',
            text: '{{ session('success') }}'
        });
    </script>
@endif
