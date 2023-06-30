@extends('plantilla.master')
@section('contenido')
    <div id="roles-component">
        <div class="card card-bordered">
            <div class="card-header">
                <roles-crear></roles-crear>
            </div>
            <div class="card-body">
                <roles-index></roles-index>
            </div>
            <div class="card-footer">
                roles del sistema
            </div>
        </div>
    </div>
    @include('configuraciones.roles.create')
@endsection
