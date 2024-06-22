@extends('plantilla.masterLogin')

@section('contenido')
    <section class="border-red-500 bg-gray-200 min-h-screen flex items-center justify-center">
        <div class="flex flex-col justify-center items-center -mt-20 ">
            <div class="mb-5">
                <div class="">
                    <img src="assets/media/logos/logo-equino.png" class="rounded-2xl" alt="imagen de la página" width="200">
                </div>
            </div>
            <div class="bg-white p-5 flex rounded-2xl shadow-lg max-w-3xl">
                <div class="md:w-1/2 px-5">
                    <h2 class="text-2xl font-bold text-[#002D74]">¿Reestablecer contraseña?</h2>
                    <p class="text-sm mt-4 text-[#002D74]">Ingrese su correo electrónico para restablecer su contraseña.</p>
                    <form class="mt-6" action="{{ route('password.email.reset') }}" method="POST" novalidate>
                        @csrf
                        <div>
                            <label class="block text-gray-700">Correo Electrónico</label>
                            <input type="email" name="email" placeholder="Ingrese su Correo Electrónico"
                                class="w-full px-4 py-3 rounded-lg bg-gray-200 mt-2 border focus:border-blue-500 focus:bg-white focus:outline-none"
                                autofocus autocomplete="off" required>
                        </div>

                        <button type="submit"
                            class="w-full block bg-blue-500 hover:bg-blue-400 focus:bg-blue-400 text-white font-semibold rounded-lg px-4 py-3 mt-6">
                            Enviar
                        </button>
                    </form>

                    <div class="text-sm flex justify-between items-center mt-3">
                        <p>¿Recordaste tu contraseña?</p>
                        <a href="{{ route('login') }}"
                            class="py-2 px-5 ml-3 bg-white border rounded-xl hover:scale-110 duration-300 border-blue-400">
                            Ingresar
                        </a>
                    </div>
                </div>

                <div class="w-1/2 hidden md:block justify-center items-center">
                    <div>
                        <img src="assets/media/misc/logos-eqt.png" class="rounded-2xl" alt="imagen de la página">
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
