<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('complemento.estilos')
    <title>Inicio</title>
    <link rel="icon" type="image/png" href="assets/media/logos/scallia-min.png">

</head>

<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true"
    data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true"
    data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true"
    data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
    <!--layout-partial:partials/_drawers.html-->
    @yield('contenido')
    @include('complemento.tema')
    @include('complemento.scripts')
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
