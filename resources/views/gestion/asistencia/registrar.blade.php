@extends('plantilla.masterLogin')
@section('contenido')
    <section class="bg-gray-200 min-h-screen flex items-center justify-center">
        <div class="flex flex-col justify-center items-center -mt-20 w-full p-5">
            <div class="mb-5">
                <div class="">
                    <img src="{{ asset('assets/media/logos/logo-equino.png') }}" class="rounded-2xl" alt="imagen de la página"
                        width="150">
                </div>
            </div>

            <div
                class="w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <div class="flex justify-end px-4 pt-4">

                </div>
                <div class="flex flex-col items-center pb-10">
                    <img class="w-14 h-14 mb-3 rounded-full shadow-lg" src="{{ asset($usuario->profile_photo_path) }}"
                        alt="Usuario" />
                    <h5 class="font-urbanist font-bold text-gray-900 dark:text-white">{{ $usuario->persona->nombre }}</h5>
                    <h5 class="font font-urbanist font-bold text-gray-900 dark:text-white">{{ $usuario->persona->paterno }}
                        {{ $usuario->persona->materno }}</h5>
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $usuario->email }}</span>

                    <div id="app" class="w-full px-5">
                        <div id="eventos-component">
                            <registro-qr :evento='{{ json_encode($evento) }}' :usuario='{{ json_encode($usuario->id) }}'
                                :qr='{{ json_encode($qr->id) }}'></registro-qr>
                        </div>
                    </div>
                    <div class="flex mt-4 md:mt-6">
                        <a href="/dashboard"
                            class=" font-urbanist inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Volver</a>
                        <a href="/logout"
                            class="py-2 px-4 ms-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Salir</a>
                    </div>
                </div>
            </div>


        </div>

    </section>
@endsection
