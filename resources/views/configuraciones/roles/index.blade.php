@extends('plantilla.master')
@section('contenido')
    <template>
        How To Install Vue 3 in Laravel 9 with Vite - TechvBlogs
    </template>
    <div class="card card-bordered">
        <div class="card-header">
            <h3 class="card-title">Roles</h3>
            <div class="card-toolbar">
                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#kt_modal_1">
                    <i class="far fa-plus text-white"></i>
                    Nuevo
                </button>
            </div>
        </div>
        <div class="card-body">
            <div id="app">
                <roles-index></roles-index>
            </div>
            <div id="app">
                <roles-index></roles-index>
            </div>
            <table id="kt_datatable_dom_positioning" class="table table-striped table-sm table-bordered">
                <thead>
                    <tr class="fw-semibold fs-7 border-bottom border-gray-200 py-4">
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th width="15%">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @foreach ($roles as $rol)
                        <tr>
                            <td>{{ $rol->name }}</td>
                            <td>{{ $rol->description }}</td>
                            <td class="align-items-center">
                                <div class="d-flex">
                                    <a href="#" class="btn btn-icon btn-active-light-primary"><i
                                            class="bi bi-eye-fill fs-4"></i></a>
                                    <a href="#" class="btn btn-icon btn-active-light-warning" data-bs-toggle="tooltip"
                                        data-bs-custom-class="tooltip-inverse" data-bs-placement="bottom"
                                        title="Editar permisos"><i class="bi bi-pencil-square fs-4"></i></a>
                                    <form action="{{ route('roles.destroy', ['role' => $rol->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-icon btn-active-light-danger eliminar-rol"
                                            data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                                            data-bs-placement="bottom" title="Eliminar rol" data-id="{{ $rol->id }}"><i
                                                class="bi bi-trash3-fill fs-4"></i></button>
                                    </form>
                                </div>
                            </td>


                        </tr>
                    @endforeach --}}



                </tbody>
            </table>


        </div>
        <div class="card-footer">
            Footer
        </div>
    </div>
    @include('configuraciones.roles.create')
@endsection
