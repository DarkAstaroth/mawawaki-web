@extends('plantilla.masterLogin')
@section('extra')
    @include('complemento.estilos')
@endsection
@section('contenido')
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <div class="order-2 d-flex flex-column flex-lg-row-fluid w-lg-50 order-lg-1">
                <div class="d-flex flex-center flex-column flex-lg-row-fluid">
                    <div class="p-10 w-lg-500px">


                        <div class="mb-5 text-center">
                            <h1 class="mb-3 text-dark fw-bolder">¡Bienvenido!</h1>
                        </div>

                        <div class="mb-1 border d-flex justify-content-center">
                            <span class="px-5 border rounded border-secondary">
                                <div class="p-2">
                                    <div class="d-flex">
                                        <div>

                                            <img src="{{ asset(auth()->user()->profile_photo_path) }}" alt="photo_user"
                                                width="40px" class="rounded-pill" />
                                        </div>
                                        <div class="mx-4 d-flex flex-column justify-content-center">
                                            <div class="text-black-500 fw-bold fs-6">{{ auth()->user()->persona->nombre }}
                                                {{ auth()->user()->persona->paterno }}
                                                {{ auth()->user()->persona->materno }}
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


                        <div id="app">
                            <usuarios-setup :usuario="{{ json_encode(auth()->user()) }}"></usuarios-setup>
                        </div>

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
