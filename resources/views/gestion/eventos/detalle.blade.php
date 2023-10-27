@extends('plantilla.master')
@section('contenido')
    <div id="eventos-component">
        <evento-detalle :evento="{{ json_encode($evento) }}"></evento-detalle>
        <evento-qrs :evento="{{ json_encode($evento) }}"></evento-qrs>
    </div>
@endsection
