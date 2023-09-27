@extends('plantilla.masterLogin')
@section('contenido')
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <div class="order-2 p-10 d-flex flex-column flex-lg-row-fluid w-lg-50 order-lg-1">
                <div class="d-flex flex-center flex-column flex-lg-row-fluid">
                    <div class="p-10 w-lg-500px">


                        <div class="mb-5 text-center">
                            <h1 class="mb-3 text-dark fw-bolder">¡Bienvenido!</h1>
                        </div>

                        <div class="mb-1 border d-flex justify-content-center">
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

                        <div class="mb-1 d-flex justify-content-center ">
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="btn text-danger fs-base fw-semibold"
                                    href="{{ route('logout') }}">¿No eres tu?</button>
                            </form>
                        </div>

                        <div class="mb-5 text-center">
                            <div class="text-gray-500 fw-bold fs-6">Completa tu registro</div>
                        </div>
                        <form class="form w-100" method="POST" action="{{ route('invitado.update', auth()->user()->id) }}">
                            @method('PUT')
                            @csrf

                            <div class="mb-8 fv-row">
                                <input type="text" placeholder="Nombres" name="nombres" autocomplete="off"
                                    class="bg-transparent form-control" value="{{ auth()->user()->nombres }}" />
                            </div>

                            <div class="mb-8 fv-row">
                                <input type="text" placeholder="Apellido paterno" name="paterno" autocomplete="off"
                                    class="bg-transparent form-control" value="{{ auth()->user()->paterno }}" />
                            </div>

                            <div class="mb-8 fv-row">
                                <input type="text" placeholder="Apellido materno" name="materno" autocomplete="off"
                                    class="bg-transparent form-control" value="{{ auth()->user()->materno }}" />
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
                                    <span class="indicator-label">Enviar solicitud</span>
                                    <span class="indicator-progress">Please wait...
                                        <span class="align-middle spinner-border spinner-border-sm ms-2"></span></span>

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

                    <a href="../../demo1/dist/index.html" class="mb-0 mb-lg-12">
                        <img alt="Logo" src="{{ asset('assets/media/logos/logo-scallia.png') }}"
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
