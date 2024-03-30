@extends('plantilla.master')
@section('bread')
    {{-- {{ Breadcrumbs::render('usuarios') }} --}}
@endsection
@section('contenido')
    <div id="avisos-component">
        <avisos-crear :usuario="{{ json_encode(auth()->user()) }}"></avisos-crear>
    </div>
@endsection
