@extends('plantilla.master')
@section('contenido')
    <div id="roles-component">
        <div class="card card-bordered">
            <roles-index></roles-index>
        </div>
    </div>
    @include('configuraciones.roles.create')
@endsection
