@extends('plantilla.master')
@section('contenido')
    Bienvenido!
    {{ auth()->user()->jsPermissions() }}

    <script type="text/javascript">
        window.Laravel = {
            csrfToken: "{{ csrf_token() }}",
            jsPermissions: {!! auth()->check()
                ? auth()->user()->jsPermissions()
                : 0 !!}
        }
    </script>
@endsection
