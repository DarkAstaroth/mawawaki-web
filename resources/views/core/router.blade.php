@extends('plantilla.master')
@section('bread')
    {{ Breadcrumbs::render('usuarios') }}
@endsection
@section('contenido')
    <div id="app">
        <router-view></router-view>
    </div>
@endsection
