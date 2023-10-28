@extends('plantilla.master')
@section('bread')
    {{-- {{ Breadcrumbs::render('usuarios') }} --}}
@endsection
@section('contenido')
    <div id="caballos-component">
        <caballos-edit :caballo="{{ json_encode($caballo) }}" :modo="{{ json_encode($modo) }}"></caballos-edit>
    </div>
@endsection
