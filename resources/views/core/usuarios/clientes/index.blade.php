@extends('plantilla.master')
@section('bread')
    {{ Breadcrumbs::render('usuarios') }}
@endsection
@section('contenido')
    <div id="app">
        <clientes-index></clientes-index>
    </div>
@endsection
