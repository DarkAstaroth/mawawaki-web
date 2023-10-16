@extends('plantilla.master')

@section('contenido')
    <div id="usuarios-component">
        <usuario-perfil :usuario='{{ json_encode($usuario) }}'></usuario-perfil>
    </div>
@endsection
