@extends('plantilla.master')
@section('bread')
    {{ Breadcrumbs::render('usuarios') }}
@endsection
@section('contenido')
    <div id="app">
        <usuarios-index></usuarios-index>
    </div>
@endsection
