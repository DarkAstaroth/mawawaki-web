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
                    <img class="w-14 h-14 mb-3 rounded-full shadow-lg" src="{{ asset(auth()->user()->profile_photo_path) }}"
                        alt="Usuario" />
                    <h5 class="font-urbanist font-bold text-gray-900 dark:text-white">{{ auth()->user()->persona->nombre }}
                    </h5>
                    <h5 class="font font-urbanist font-bold text-gray-900 dark:text-white">
                        {{ auth()->user()->persona->paterno }}
                        {{ auth()->user()->persona->materno }}</h5>
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</span>


                </div>

                <div class="px-5">
                    <div
                        class="flex items-center rounded-lg font-semibold {{ auth()->user()->email_verified_at !== null ? 'bg-emerald-100' : 'bg-red-100' }} d-flex align-items-center p-5 mb-5 text-start">
                        <i
                            class="fi  {{ auth()->user()->email_verified_at !== null ? 'fi-ss-check-circle' : 'fi-sr-circle-xmark' }} fs-2hx  {{ auth()->user()->email_verified_at !== null ? 'text-emerald-700' : 'text-red-700' }} me-4"><span
                                class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                        <div class="d-flex flex-column">
                            <h4
                                class="mb-1 {{ auth()->user()->email_verified_at !== null ? 'text-emerald-700' : 'text-red-700' }}">
                                Verificación Email</h4>
                        </div>
                    </div>

                    <div
                        class="flex items-center rounded-lg font-semibold  {{ auth()->user()->solicitud ? 'bg-emerald-100' : 'bg-red-100' }} d-flex align-items-center p-5 mb-5 text-start">
                        <i
                            class="fi {{ auth()->user()->solicitud ? 'fi-ss-check-circle' : 'fi-sr-circle-xmark' }} fs-2hx  {{ auth()->user()->solicitud ? 'text-emerald-700' : 'text-red-700' }} me-4"><span
                                class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                        <div class="d-flex flex-column">
                            <h4 class="mb-1 {{ auth()->user()->solicitud ? 'text-emerald-700' : 'text-red-700' }}">
                                Solicitud de registro</h4>
                        </div>
                    </div>

                    <div
                        class="flex items-center rounded-lg font-semibold {{ auth()->user()->verificada ? 'bg-emerald-100' : 'bg-amber-100' }} d-flex align-items-center p-5 mb-5 text-start">
                        <i
                            class="fi {{ auth()->user()->verificada ? 'fi-bs-portrait' : 'fi-bs-portrait' }} fs-2hx  {{ auth()->user()->verificada ? 'text-emerald-700' : 'text-amber-500' }} me-4"><span
                                class="path1"></span><span class="path2"></span><span class="path3"></span></i>

                        <div
                            class="flex flex-col {{ auth()->user()->verificada ? 'text-emerald-700' : 'text-amber-500' }}">

                            <h4 class="flex-grow-1 mb-1 ">
                                Verificación de cuenta</h4>
                            <span class="font-light text-sm  ">
                                {{ auth()->user()->verificada ? 'La cuenta ha sido aprobada' : 'La cuenta esta siendo revisada por un administrador' }}
                            </span>
                        </div>

                    </div>


                    <div
                        class="flex items-center rounded-lg font-semibold {{ auth()->user()->estado ? 'bg-emerald-100' : 'bg-red-100' }} d-flex align-items-center p-5 mb-5 text-start">
                        <i
                            class="fi {{ auth()->user()->estado ? 'fi-bs-portrait' : 'fi-bs-portrait' }} fs-2hx  {{ auth()->user()->estado ? 'text-emerald-700' : 'text-red-700' }} me-4"><span
                                class="path1"></span><span class="path2"></span><span class="path3"></span></i>

                        <h4 class="flex-grow-1 mb-1 {{ auth()->user()->estado ? 'text-emerald-700' : 'text-red-700' }}">


                            <div class="flex flex-col {{ auth()->user()->estado ? 'text-emerald-700' : 'text-red-700' }}">

                                <h4 class="flex-grow-1 mb-1 ">
                                    Estado de la cuenta</h4>
                                <span class="font-light text-sm  ">
                                    {{ auth()->user()->estado ? 'Cuenta activa' : 'Cuenta Inactivada' }}
                        </h4>


                        </span>
                    </div>

                </div>
            </div>

            <div class="flex mt-4 md:mt-6 w-full justify-center pb-10">
                <a href="/dashboard"
                    class=" font-urbanist inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Volver</a>
                <a href="/logout"
                    class="py-2 px-4 ms-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Salir</a>
            </div>
        </div>
        </div>
    </section>
@endsection
