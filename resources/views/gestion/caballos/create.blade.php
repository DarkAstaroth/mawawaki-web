@extends('plantilla.master')
@section('bread')
    {{-- {{ Breadcrumbs::render('usuarios') }} --}}
@endsection
@section('contenido')
    <div id="caballos-component">
        <caballos-create :caballo="yes"></caballos-create>
    </div>
@endsection
