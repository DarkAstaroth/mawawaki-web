@extends('plantilla.masterLogin')
@section('contenido')
    <div class="d-flex flex-column flex-root" id="kt_app_root">

        <div class="d-flex flex-column flex-lg-row flex-column-fluid">

            <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">

                <div class="d-flex flex-center flex-column flex-lg-row-fluid">

                    <div class="w-lg-500px p-10">

                        <form novalidate="novalidate" id="kt_sign_in_form" class="form w-100"
                            action="{{ route('password.email') }}" method="POST">
                            @csrf
                            <div class="text-center mb-10">
                                <h1 class="text-dark fw-bolder mb-3">¿Reestablecer contraseña?</h1>
                                <div class="text-gray-500 fw-semibold fs-6">Ingrese su correo electrónico para restablecer
                                    su contraseña.</div>
                            </div>


                            <div class="fv-row mb-8">
                                <input type="text" placeholder="Correo electrónico" name="email" autocomplete="off"
                                    class="form-control bg-transparent" />
                            </div>


                            <div class="d-flex flex-wrap justify-content-center pb-lg-0">
                                <button type="submit" class="btn btn-primary me-4">
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


            <div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2"
                style="background-image: url(assets/media/misc/auth-bg.png)">

                <div class="d-flex flex-column flex-center py-7 py-lg-15 px-5 px-md-15 w-100">

                    <a href="../../demo1/dist/index.html" class="mb-0 mb-lg-12">
                        <img alt="Logo" src="assets/media/logos/custom-1.png" class="h-60px h-lg-75px" />
                    </a>


                    <img class="d-none d-lg-block mx-auto w-275px w-md-50 w-xl-500px mb-10 mb-lg-20"
                        src="assets/media/misc/auth-screens.png" alt="" />


                    <h1 class="d-none d-lg-block text-white fs-2qx fw-bolder text-center mb-7">Fast, Efficient and
                        Productive</h1>


                    <div class="d-none d-lg-block text-white fs-base text-center">In this kind of post,
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
