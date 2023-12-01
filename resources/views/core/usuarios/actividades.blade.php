@extends('plantilla.master')

@section('contenido')
    <div id="usuarios-component">
        <usuario-actividades :usuario='{{ json_encode($usuario) }}'></usuario-actividades>
    </div>
@endsection
