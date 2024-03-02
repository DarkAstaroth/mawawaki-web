@extends('plantilla.masterLogin')
@section('contenido')
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <style>
            body {
                background-image: url({{ asset('assets/media/auth/bg3.jpg') }});
            }

            [data-bs-theme="dark"] body {
                background-image: url({{ asset('assets/media/auth/bg3-dark.jpg') }});
            }
        </style>


        <div class="d-flex flex-column flex-center flex-column-fluid">
            <div class="d-flex flex-column flex-center text-center p-10">
                <div class="card card-flush w-lg-650px py-5">
                    <div class="card-body py-15 py-lg-20">
                        <div class="mb-10">
                            <a href="../../demo1/dist/index.html" class="">
                                <img alt="Logo" src="{{ asset('assets/media/logos/logo-equino.png') }}"
                                    width="220" />
                            </a>
                        </div>

                        <div
                            class="alert alert-dismissible bg-light-info d-flex flex-center flex-column py-10 px-10 px-lg-20 mb-10">

                            <i class="ki-duotone ki-information-5 fs-5tx text-info mb-5"><span class="path1"></span><span
                                    class="path2"></span><span class="path3"></span></i>

                            <div class="text-center">
                                <h1 class="fw-bold mb-5">Evento caducado</h1>
                                <div class="separator separator-dashed border-info opacity-25 mb-5"></div>
                                <div class="mb-9 text-gray-900">
                                    ¡Lo sentimos! Este evento ha caducado y <strong>ya no está disponible</strong> para
                                    registrar
                                    asistencia. Si tienes alguna pregunta o necesitas más información, no dudes en
                                    cantactar al administrador. ¡Gracias!
                                </div>

                                <div class="d-flex flex-center flex-wrap">
                                    <a href="#" class="btn btn-outline btn-outline-info btn-active-info m-2">Salir</a>
                                    <a href="#" class="btn btn-info m-2">Ir a Inicio</a>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
