@extends('plantilla.master')
@section('contenido')
    <div id="eventos-component">
        <evento-detalle :evento="{{ json_encode($evento) }}"></evento-detalle>
    </div>
@endsection
