@extends('plantilla.master')
@section('bread')
    {{-- {{ Breadcrumbs::render('usuarios') }} --}}
@endsection
@section('contenido')
    <div id="caballos-component">
        <caballos-index></caballos-index>
    </div>
@endsection
