@extends('plantilla.master')
@section('contenido')
    <div id="eventos-component">
        <evento-qrs :evento="{{ json_encode($evento) }}"></evento-qrs>
    </div>
@endsection
