@extends('plantilla.masterLogin')

@section('contenido')
    <section class="border-red-500 bg-gray-200 min-h-screen flex items-center justify-center">
        <div class="flex flex-col justify-center items-center -mt-20 ">
            <div class="mb-5">
                <div class="">
                    <img src="{{ asset('assets/media/logos/logo-equino.png') }}" class="rounded-2xl" alt="imagen de la página"
                        width="200">
                </div>
            </div>
            <div class="bg-white p-5 flex rounded-2xl shadow-lg max-w-3xl">
                <div class="md:w-full px-5">
                    <h2 class="text-2xl font-bold text-[#002D74]">Crear Nueva Cuenta</h2>
                    <form class="mt-6" action="{{ route('usuario.crear') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700">Nombres</label>
                            <input type="text" name="nombres" placeholder="Ej: Juan Carlos"
                                class="w-full px-4 py-3 rounded-lg bg-gray-200 mt-2 border focus:border-blue-500 focus:bg-white focus:outline-none"
                                value="{{ old('nombres') }}" style="text-transform: uppercase;" required>
                            @error('nombres')
                                <div class="text-red-500 text-sm">El campo nombres es obligatorio.</div>
                            @enderror
                        </div>
                        <div class="flex gap-4 mb-4">
                            <div class="w-1/2">
                                <label class="block text-gray-700">Apellido Paterno</label>
                                <input type="text" name="paterno" placeholder="Ej: Perez"
                                    class="w-full px-4 py-3 rounded-lg bg-gray-200 mt-2 border focus:border-blue-500 focus:bg-white focus:outline-none"
                                    value="{{ old('paterno') }}" style="text-transform: uppercase;" required>
                                @error('paterno')
                                    <div class="text-red-500 text-sm">El campo apellido paterno es obligatorio.</div>
                                @enderror
                            </div>
                            <div class="w-1/2">
                                <label class="block text-gray-700">Apellido Materno</label>
                                <input type="text" name="materno" placeholder="Ej: Torrez"
                                    class="w-full px-4 py-3 rounded-lg bg-gray-200 mt-2 border focus:border-blue-500 focus:bg-white focus:outline-none"
                                    value="{{ old('materno') }}" style="text-transform: uppercase;" required>
                                @error('materno')
                                    <div class="text-red-500 text-sm">El campo apellido materno es obligatorio.</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700">Correo Electrónico</label>
                            <input type="email" name="email" placeholder="Correo electrónico"
                                class="w-full px-4 py-3 rounded-lg bg-gray-200 mt-2 border focus:border-blue-500 focus:bg-white focus:outline-none"
                                value="{{ old('email') }}" required>
                            @error('email')
                                <div class="text-red-500 text-sm">El correo electrónico es obligatorio y debe ser válido.</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700">Contraseña</label>
                            <input type="password" id="password" name="password"
                                placeholder="Establece una contraseña segura"
                                class="w-full px-4 py-3 rounded-lg bg-gray-200 mt-2 border focus:border-blue-500 focus:bg-white focus:outline-none"
                                required>
                            <div id="passwordStrengthMessage" class="text-sm mt-2"></div>
                            @error('password')
                                <div class="text-red-500 text-sm">La contraseña es obligatoria y debe tener al menos 8
                                    caracteres.</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700">Confirmar Contraseña</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                placeholder="Repite la contraseña"
                                class="w-full px-4 py-3 rounded-lg bg-gray-200 mt-2 border focus:border-blue-500 focus:bg-white focus:outline-none"
                                required>
                            <div id="passwordMessage" class="text-sm mt-2"></div>

                            <!-- Mensaje de fortaleza de contraseña -->
                            @error('password_confirmation')
                                <div class="text-red-500 text-sm">La confirmación de contraseña no coincide con la contraseña.
                                </div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="toc" value="1"
                                    class="form-checkbox h-5 w-5 text-blue-600 transition duration-150 ease-in-out rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <span class="ml-2 text-sm text-gray-700">
                                    Acepto los <a href="#" class="text-blue-500 hover:underline">Términos y
                                        Condiciones</a>
                                </span>
                            </label>
                            @error('toc')
                                <div class="text-red-500 text-sm">Debes aceptar los términos y condiciones.</div>
                            @enderror
                        </div>
                        <button type="submit"
                            class="w-full bg-blue-500 hover:bg-blue-400 focus:bg-blue-400 text-white font-semibold rounded-lg px-4 py-3">
                            Enviar Solicitud
                        </button>
                    </form>
                    <div class="text-sm flex justify-between items-center mt-3">
                        <p>¿Ya tienes una cuenta?</p>
                        <a href="{{ route('login') }}"
                            class="py-2 px-5 ml-3 bg-white border rounded-xl hover:scale-110 duration-300 border-blue-400">Ingresar</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const passwordConfirmationInput = document.getElementById('password_confirmation');
            const passwordMessage = document.getElementById('passwordMessage');
            const passwordStrengthMessage = document.getElementById('passwordStrengthMessage');

            function validatePassword() {
                if (passwordInput.value !== passwordConfirmationInput.value) {
                    passwordMessage.textContent = 'Las contraseñas no coinciden.';
                    passwordMessage.classList.remove('text-green-500');
                    passwordMessage.classList.add('text-red-500');
                } else {
                    passwordMessage.textContent = 'Las contraseñas coinciden.';
                    passwordMessage.classList.remove('text-red-500');
                    passwordMessage.classList.add('text-green-500');
                }

                let password = passwordInput.value;
                let strength = 0;

                if (password.length >= 8) {
                    strength += 1;
                }

                if (strength === 0) {
                    passwordStrengthMessage.textContent = 'La contraseña es muy débil.';
                    passwordStrengthMessage.classList.remove('text-green-500');
                    passwordStrengthMessage.classList.add('text-red-500');
                } else if (strength === 1) {
                    passwordStrengthMessage.textContent = 'La contraseña es aceptable.';
                    passwordStrengthMessage.classList.remove('text-red-500');
                    passwordStrengthMessage.classList.add('text-green-500');
                } else {
                    passwordStrengthMessage.textContent = 'La contraseña es segura.';
                    passwordStrengthMessage.classList.remove('text-red-500', 'text-yellow-500');
                    passwordStrengthMessage.classList.add('text-green-500');
                }
            }

            // Escuchar eventos de entrada para validar contraseña y fortaleza
            passwordConfirmationInput.addEventListener('input', validatePassword);
            passwordInput.addEventListener('input', validatePassword);
        });
    </script>
@endsection
