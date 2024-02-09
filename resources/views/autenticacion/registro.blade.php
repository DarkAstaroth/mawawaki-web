@extends('plantilla.masterLogin')
@section('contenido')
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <div class="order-2 p-10 d-flex flex-column flex-lg-row-fluid w-lg-50 order-lg-1">
                <div class="d-flex flex-center flex-column flex-lg-row-fluid">
                    <div class="p-10 w-lg-500px">


                        <div class="mb-5 text-center">
                            <h1 class="mb-3 text-dark fw-bolder">Crear Nueva cuenta</h1>
                        </div>

                        <div class="mb-5 text-center">
                            <div class="text-gray-500 fw-bold fs-6">Completa tu registro</div>
                        </div>
                        <form class="form w-100" novalidate="novalidate" id="kt_sign_up_form" method="POST"
                            action="{{ route('usuario.crear') }}">
                            @csrf

                            <div class="mb-8 fv-row">
                                <input type="text" placeholder="Nombres" name="nombres" autocomplete="off"
                                    class="bg-transparent form-control" />
                            </div>

                            <div class="mb-8 fv-row">
                                <input type="text" placeholder="Apellido paterno" name="paterno" autocomplete="off"
                                    class="bg-transparent form-control" />
                            </div>

                            <div class="mb-8 fv-row">
                                <input type="text" placeholder="Apellido materno" name="materno" autocomplete="off"
                                    class="bg-transparent form-control" />
                            </div>

                            <div class="mb-8 fv-row">
                                <input type="email" placeholder="Correo electrónico" name="email" autocomplete="off"
                                    class="bg-transparent form-control" />
                            </div>


                            <div class="mb-8 fv-row" data-kt-password-meter="true">

                                <div class="mb-1">

                                    <div class="mb-3 position-relative">
                                        <input class="bg-transparent form-control" type="password" placeholder="Contraseña"
                                            name="password" autocomplete="off" />
                                        <span
                                            class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                            data-kt-password-meter-control="visibility">
                                            <i class="ki-duotone ki-eye-slash fs-2"></i>
                                            <i class="ki-duotone ki-eye fs-2 d-none"></i>
                                        </span>
                                    </div>


                                    <div class="mb-3 d-flex align-items-center" data-kt-password-meter-control="highlight">
                                        <div class="rounded flex-grow-1 bg-secondary bg-active-success h-5px me-2"></div>
                                        <div class="rounded flex-grow-1 bg-secondary bg-active-success h-5px me-2"></div>
                                        <div class="rounded flex-grow-1 bg-secondary bg-active-success h-5px me-2"></div>
                                        <div class="rounded flex-grow-1 bg-secondary bg-active-success h-5px"></div>
                                    </div>

                                </div>


                                <div class="text-muted">
                                    Utilice 8 o más caracteres con una combinación de letras, números y símbolos.
                                </div>

                            </div>


                            <div class="mb-8 fv-row">

                                <input placeholder="Repite la contraseña" name="password_confirmation" type="password"
                                    autocomplete="off" class="bg-transparent form-control" />

                            </div>


                            <div class="mb-8 fv-row">
                                <label class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="toc" value="1" />
                                    <span class="text-gray-700 form-check-label fw-semibold fs-base ms-1">Acepto los
                                        <a href="#" class="ms-1 link-primary">Terminos y Condiciones</a></span>
                                </label>
                            </div>


                            <div class="mb-10 d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <span class="indicator-label">Enviar solicitud</span>
                                        <span id="loading" style="display: none;"
                                            class="spinner-border spinner-border-sm align-middle ms-2">
                                        </span>

                                    </div>

                                </button>
                            </div>


                            <div class="text-center text-gray-500 fw-semibold fs-6">¿Ya tienes una cuenta?
                                <a href="{{ route('login') }}" class="link-primary fw-semibold">Ingresar</a>
                            </div>

                        </form>

                    </div>

                </div>
            </div>


            <div class="order-1 d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-lg-2"
                style="background-image: url({{ asset('assets/media/misc/auth-bg.png)') }}">

                <div class="px-5 d-flex flex-column flex-center py-7 py-lg-15 px-md-15 w-100">

                    <a href="{{ route('home') }}" class="mb-0 mb-lg-12">
                        <img alt="Logo" src="{{ asset('assets/media/logos/logo-unido.png') }}"
                            class="h-60px h-lg-75px" />
                    </a>

                    <img class="mx-auto mb-10 d-none d-lg-block w-275px w-md-50 w-xl-500px mb-lg-20"
                        src="{{ asset('assets/media/misc/auth-screens.png') }}" alt="" />


                    <h1 class="text-center text-white d-none d-lg-block fs-2qx fw-bolder mb-7">Fast, Efficient and
                        Productive</h1>


                    <div class="text-center text-white d-none d-lg-block fs-base">In this kind of post,
                        <a href="#" class="opacity-75-hover text-warning fw-bold me-1">the blogger</a>introduces a
                        person they’ve interviewed
                        <br />and provides some background information about
                        <a href="#" class="opacity-75-hover text-warning fw-bold me-1">the interviewee</a>and their
                        <br />work following this is a transcript of the interview.
                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection
