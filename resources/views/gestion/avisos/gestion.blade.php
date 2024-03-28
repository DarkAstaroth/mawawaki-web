@extends('plantilla.master')
@section('bread')
    {{-- {{ Breadcrumbs::render('usuarios') }} --}}
@endsection
@section('contenido')
    <div id="avisos-component">
        <avisos-gestion></avisos-gestion>
    </div>
@endsection
