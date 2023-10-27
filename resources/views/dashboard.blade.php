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
    <div class="visible-print text-center">
        {!! QrCode::size(200)->generate(Request::url()); !!}
        <p>Scan me to return to the original page.</p>
    </div>
@endsection
