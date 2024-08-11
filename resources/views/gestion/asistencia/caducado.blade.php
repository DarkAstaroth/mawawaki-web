@extends('plantilla.masterLogin')

@section('contenido')
    <section class="bg-gray-200 min-h-screen flex items-center justify-center">
        <div class="flex flex-col justify-center items-center -mt-20 ">
            <div class="mb-5">
                <div class="">
                    <img src="{{ asset('assets/media/logos/logo-equino.png') }}" class="rounded-2xl" alt="imagen de la página"
                        width="150">
                </div>
            </div>
            <div class="p-5 flex rounded-2xl w-full sm:w-1/2">
                <div class="md:w-full px-5">
                    <div id=""
                        class="p-4 mb-4 text-indigo-800 border border-indigo-300 rounded-lg bg-white dark:bg-gray-800 dark:text-indigo-400 dark:border-indigo-800"
                        role="alert">
                        <div class="flex items-center">
                            <div class="flex flex-center flex-col items-center w-full">
                                <i class=" fi fi-br-calendar-clock text-4xl mt-2"></i>
                                <h3 class="text-2xl font-bold mt-5 font-urbanist">Evento finalizado</h3>
                            </div>
                        </div>
                        <div class="mt-2 text-center text-sm">
                            ¡Lo sentimos! Este evento ha finalizado y <strong>ya no está disponible</strong> para
                            registrar
                            asistencia. Gracias
                        </div>
                        <div class="flex justify-center mt-4 text-sm">

                            <a href="/dashboard"
                                class="cursor-pointer
                                text-center block bg-indigo-500 hover:bg-indigo-400 focus:bg-indigo-400 text-white font-semibold
                                rounded-lg px-4 py-2">
                                <div class="flex gap-2 items-center">

                                    <i class="fi fi-br-home"></i>
                                    <div class="text-sm font-urbanist">

                                        Volver
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </section>
@endsection
