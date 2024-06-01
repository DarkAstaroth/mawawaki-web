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
                                <img alt="Logo" src="{{ asset('assets/media/logos/logo-equino.png') }}"
                                    width="220" />
                            </a>
                        </div>

                        <div class="mb-1  d-flex justify-content-center mb-5">
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

                        <div class="mb-1 d-flex flex-column  w-100 mb-5 align-items-center">
                            <span class="mb-5">
                                <div class="p-2">
                                    <div class="d-flex">
                                        <div class="mx-4 d-flex flex-column">

                                            {{-- <div class="text-gray-500 fw-semibold fs-base ">{{ $evento->descripcion }}
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </span>

                            <div id="app" class="w-100">
                                <div id="eventos-component">
                                    <registro-qr :evento='{{ json_encode($evento) }}'
                                        :usuario='{{ json_encode($usuario->id) }}'
                                        :qr='{{ json_encode($qr->id) }}'></registro-qr>
                                </div>
                            </div>
                        </div>

                        <div class="">
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
