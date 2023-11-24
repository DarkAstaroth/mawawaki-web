@extends('plantilla.master')
@section('contenido')
    <div id="clientes-component">
        <clientes-pacientes :usuario='{{ json_encode($usuario) }}'></clientes-pacientes>
    </div>
@endsection
