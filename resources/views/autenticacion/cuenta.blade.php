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
                        <div class="mb-14">
                            <a href="../../demo1/dist/index.html" class="">
                                <img alt="Logo" src="{{ asset('assets/media/logos/logo-scallia-light.png') }}"
                                    width="120" />
                            </a>
                        </div>

                        <h1 class="fw-bolder text-gray-900 mb-5">
                            Estado de cuenta
                        </h1>


                        <div class="mb-1  d-flex justify-content-center mb-10">
                            <span class="px-5 border rounded border-secondary">
                                <div class="p-2">
                                    <div class="d-flex">
                                        <img src="{{ asset(auth()->user()->profile_photo_path) }}" alt="photo_user"
                                            width="40px" class="rounded-pill" />
                                        <div class="mx-4 d-flex flex-column justify-content-center">
                                            <div class="text-black-500 fw-bold fs-6">{{ auth()->user()->name }}
                                            </div>
                                            <div class="text-gray-500 fw-semibold fs-base ">{{ auth()->user()->email }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </span>
                        </div>

                        <div
                            class="alert  {{ auth()->user()->email_verified_at !== null ? 'alert-success' : 'alert-danger' }} d-flex align-items-center p-5 mb-5 text-start">
                            <i
                                class="ki-duotone {{ auth()->user()->email_verified_at !== null ? 'ki-shield-tick' : 'ki-shield-cross' }} fs-2hx  {{ auth()->user()->email_verified_at !== null ? 'text-success' : 'text-danger' }} me-4"><span
                                    class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            <div class="d-flex flex-column">
                                <h4
                                    class="mb-1 {{ auth()->user()->email_verified_at !== null ? 'text-success' : 'text-danger' }}">
                                    Verificación Email</h4>
                            </div>
                        </div>

                        <div
                            class="alert  {{ auth()->user()->solicitud ? 'alert-success' : 'alert-danger' }} d-flex align-items-center p-5 mb-5 text-start">
                            <i
                                class="ki-duotone {{ auth()->user()->solicitud ? 'ki-shield-tick' : 'ki-shield-cross' }} fs-2hx  {{ auth()->user()->solicitud ? 'text-success' : 'text-danger' }} me-4"><span
                                    class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            <div class="d-flex flex-column">
                                <h4 class="mb-1 {{ auth()->user()->solicitud ? 'text-success' : 'text-danger' }}">
                                    Solicitud de registro</h4>
                            </div>
                        </div>

                        <div
                            class="alert {{ auth()->user()->verificada ? 'alert-success' : 'alert-warning' }} d-flex align-items-center p-5 mb-5 text-start">
                            <i
                                class="ki-duotone {{ auth()->user()->verificada ? 'ki-lock-3' : 'ki-lock-3' }} fs-2hx  {{ auth()->user()->verificada ? 'text-success' : 'text-warning' }} me-4"><span
                                    class="path1"></span><span class="path2"></span><span class="path3"></span></i>

                            <h4
                                class="flex-grow-1 mb-1 {{ auth()->user()->verificada ? 'text-success' : 'text-warning' }}">
                                Verificación de cuenta</h4>
                            <i class="ki-duotone {{ auth()->user()->verificada ? 'ki-verify' : 'ki-question' }} fs-2hx  {{ auth()->user()->verificada ? 'text-success' : 'text-warning' }} me-4"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                title="{{ auth()->user()->verificada ? 'La cuenta ha sido aprobada' : 'La cuenta esta siendo revisada por un administrador' }}"><span
                                    class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                        </div>

                        <div class="">
                            <a href="#" class="btn btn-icon-primary btn-text-primary">
                                <i class="ki-duotone ki-home fs-1"><span class="path1"></span><span
                                        class="path2"></span></i>
                                Inicio
                            </a>
                            <a href="/logout" class="btn btn-icon-danger btn-text-danger">
                                <i class="ki-duotone ki-cross-square fs-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                                Salir
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
