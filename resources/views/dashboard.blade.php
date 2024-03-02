@extends('plantilla.master')
@section('contenido')
    Bienvenido!
    <div class="d-flex flex-column align-items-center gap-10 w-100">

        <div class="visible-print text-center">
            {!! QrCode::size(200)->generate(Request::url(strval(env('APP_URL')))) !!}
        </div>
        <div id="app" class="w-100">
            <dashboard-eventos></dashboard-eventos>
        </div>
    </div>
@endsection
