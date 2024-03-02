@extends('plantilla.masterLogin')
@section('contenido')
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
                <div class="d-flex flex-center flex-column flex-lg-row-fluid">
                    <div class="w-lg-500px w-100 mt-20">


                        <div class="text-center mb-11">
                            <h1 class="text-dark fw-bolder mb-3">Bienvenido</h1>
                        </div>
                        {{-- <div class="row g-3 mb-9">
                            <div class="col-md-12">
                                <a href="auth/google"
                                    class="btn btn-flex btn-outline btn-text-gray-700 btn-active-color-primary bg-state-light flex-center text-nowrap w-100">
                                    <img alt="Logo" src="assets/media/svg/brand-logos/google-icon.svg"
                                        class="h-15px me-3" />Ingresar con Google</a>
                            </div>

                        </div> --}}

                        <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form"
                            action="{{ route('verificar.login') }}" method="POST">
                            @csrf
                            @method('POST')
                            <div class="separator separator-content my-14">
                                <span class="text-gray-500 fw-semibold fs-7">Ingresar con tu correo</span>
                            </div>


                            <div class="fv-row mb-8">

                                <input type="text" placeholder="Correo electrónico" name="email" autocomplete="off"
                                    class="form-control bg-transparent" />

                            </div>

                            <div class="fv-row mb-3">
                                <input type="password" placeholder="Contraseña" name="password" autocomplete="off"
                                    class="form-control bg-transparent" />
                            </div>


                            <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                                <div></div>

                                <a href="{{ route('reset.pass') }}" class="link-primary">¿Olvidaste tu contraseña?</a>

                            </div>


                            <div class="d-grid mb-10">
                                <button type="submit" class="btn btn-primary">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <span class="indicator-label">Ingresar</span>
                                        <span id="loading" style="display: none;"
                                            class="spinner-border spinner-border-sm align-middle ms-2">
                                        </span>

                                    </div>
                                </button>
                            </div>

                            <div class="text-gray-500 text-center fw-semibold fs-6">¿No tienes cuenta?
                                <a href="{{ route('login.registro') }}" class="link-primary">Registrate</a>
                            </div>

                        </form>

                    </div>

                </div>
            </div>


            <div
                class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2 bg-dark bg-emerald-100   ">

                <div class="d-flex flex-column flex-center py-7 py-lg-15 px-5 px-md-15 w-100">

                    <a href="{{ route('home') }}" class="mb-0 mb-lg-12">
                        <img alt="Logo" src="assets/media/logos/logo-unido.png" class="h-60px h-lg-75px" />
                    </a>

                    <img class="d-none d-lg-block mx-auto w-275px w-md-50 w-xl-500px mb-10 mb-lg-20"
                        src="assets/media/misc/Login-image.png" alt="" />


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
