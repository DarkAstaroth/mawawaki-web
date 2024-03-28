@extends('plantilla.master')
@section('bread')
    {{-- {{ Breadcrumbs::render('usuarios') }} --}}
@endsection
@section('contenido')
    <div id="avisos-component">
        <avisos-crear></avisos-crear>
    </div>
@endsection
