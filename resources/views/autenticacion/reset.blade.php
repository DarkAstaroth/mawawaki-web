@extends('plantilla.masterLogin')
@section('contenido')
    <div class="d-flex flex-column flex-root" id="kt_app_root">

        <div class="d-flex flex-lg-row flex-column-fluid">

            <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">

                <div class="d-flex flex-center flex-column flex-lg-row-fluid">

                    <div class="w-lg-500px p-10">

                        <form class="form w-100" id="kt_new_password_form" action="{{ route('password.update') }}"
                            method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="text-center mb-10">

                                <h1 class="text-dark fw-bolder mb-3">Cambiar contraseña</h1>


                                <div class="text-gray-500 fw-semibold fs-6">Ingresa una nueva contraseña</div>
                            </div>

                            <div class="fv-row mb-8">
                                <input type="hidden" name="token" value="{{ $token }}">
                                <input type="text" placeholder="Email" name="email" autocomplete="off"
                                    class="form-control bg-transparent" value="{{ request()->email }}" readonly />

                            </div>

                            <div class="mb-8 fv-row" data-kt-password-meter="true">

                                <div class="mb-1">

                                    <div class="mb-3 position-relative">
                                        <input class="bg-transparent form-control" type="password"
                                            placeholder="Nueva contraseña" name="password" autocomplete="off" />
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

                            <div class="d-flex flex-wrap justify-content-center pb-lg-0">
                                <button type="submit" id="kt_password_reset_submit" class="btn btn-primary me-4">

                                    <div class="d-flex justify-content-center align-items-center">
                                        <span class="indicator-label">Enviar</span>
                                        <span id="loading" style="display: none;"
                                            class="spinner-border spinner-border-sm align-middle ms-2">
                                        </span>

                                    </div>

                                </button>
                                <a href="{{ route('login') }}" class="btn btn-light">Cancelar</a>
                            </div>

                        </form>

                    </div>

                </div>



            </div>



            <div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2 bg-dark">

                <div class="d-flex flex-column flex-center py-7 py-lg-15 px-5 px-md-15 w-100">

                    <a href="../../demo1/dist/index.html" class="mb-0 mb-lg-12">
                        <img alt="Logo" src="{{ asset('assets/media/logos/logo-unido.png') }}"
                            class="h-60px h-lg-75px" />
                    </a>


                    <img class="d-none d-lg-block mx-auto w-275px w-md-50 w-xl-500px mb-10 mb-lg-20"
                        src="{{ asset('assets/media/misc/Login-image.png') }}" alt="" />


                    <h1 class="d-none d-lg-block text-white fs-2qx fw-bolder text-center mb-7">Una Mirada al Éxito de la
                        Terapia con Caballos
                    </h1>


                    <div class="d-none d-lg-block text-white fs-base text-center">IConecta con la naturaleza, siente la<br>
                        poderosa presencia equina y desata tu bienestar emocional. En nuestro sitio, explorarás cómo
                        este<br>
                        enfoque innovador acelera tu camino hacia el éxito personal. ¡Bienvenido a una terapia que te<br>
                        impulsa hacia la mejor versión de ti mismo!
                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection
