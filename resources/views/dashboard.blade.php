@extends('plantilla.master')
@section('contenido')
    Bienvenido!

    <div class="visible-print text-center">
        {!! QrCode::size(200)->generate(Request::url('http://localhost:8000')) !!}

    </div>
@endsection
