@extends('plantilla.master')
@section('bread')
    {{ Breadcrumbs::render('roles.permisos', $rol) }}
@endsection
@section('contenido')
    <div id="roles-component">
        <permiso-rol :rol="{{ json_encode($rol) }}" :permisos_rol="{{ json_encode($permisosRol) }}"></permiso-rol>
    </div>
@endsection
