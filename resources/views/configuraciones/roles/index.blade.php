@extends('plantilla.master')
@section('contenido')
    <div class="card card-bordered">
        <div class="card-header">
            <h3 class="card-title">Roles</h3>
            <div class="card-toolbar">
                <button type="button" class="btn btn-sm btn-success">
                    <i class="far fa-plus text-white"></i>
                    Nuevo
                </button>
            </div>
        </div>
        <div class="card-body">
            <table id="kt_datatable_dom_positioning"
                class="table table-sm table-responsive table-striped gy-5 gs-7 border rounded dataTable no-footer">
                <thead>
                    <tr class="fw-bold fs-6 text-gray-800 px-7">
                        <th>Name</th>
                        <th>Position</th>
                        <th>Salary</th>
                        <th>Office</th>
                        <th>Extn.</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Tiger Nixon</td>
                        <td>System Architect</td>
                        <td>Edinburgh</td>
                        <td>61</td>
                        <td>2011/04/25</td>
                        <td>
                            <a href="#" class="btn btn-icon btn-active-light-primary"><i
                                    class="bi bi-eye-fill fs-4 "></i></a>
                            <a href="#" class="btn btn-icon btn-active-light-warning"><i
                                    class="bi bi-pencil-square fs-4 "></i></a>
                            <a href="#" class="btn btn-icon btn-active-light-danger"><i
                                    class="bi bi-trash3-fill fs-4 "></i></a>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
        <div class="card-footer">
            Footer
        </div>
    </div>
@endsection
