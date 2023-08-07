@extends('plantilla.master')
@section('bread')
    {{ Breadcrumbs::render('roles') }}
@endsection
@section('contenido')
    <div id="roles-component">
        <div class="card card-bordered">
            <roles-index></roles-index>
        </div>
    </div>
@endsection
