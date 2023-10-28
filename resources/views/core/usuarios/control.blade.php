@extends('plantilla.master')

@section('contenido')
    <div id="usuarios-component">
        <usuario-control :usuario='{{ json_encode($usuario) }}'></usuario-control>
    </div>
@endsection
