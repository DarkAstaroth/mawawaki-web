@extends('plantilla.master')
@section('contenido')
    <div id="terapias-component">
        <terapias-detalles :servicio='{{ json_encode($servicio) }}'></eventos-index>
    </div>
@endsection
