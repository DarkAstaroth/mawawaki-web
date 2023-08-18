@extends('plantilla.master')
@section('bread')
    {{ Breadcrumbs::render('usuarios') }}
@endsection
@section('contenido')
    <div id="usuarios-component">
        <usuarios-index></usuarios-index>
    </div>
@endsection
