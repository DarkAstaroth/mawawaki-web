<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <img alt="Logo" src="{{ asset('assets/media/logos/logo-equino.png') }}" width="220px" />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Antes de continuar, Debes verificar tu dirección de correo electrónico haciendo clic en botón para enviar el enlace de verificación.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ __('Se ha enviado un nuevo enlace de verificación a su dirección de correo electrónico ') }}
                {{ auth()->user()->email }}
            </div>
        @endif

        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <div>
                    <x-button type="submit">
                        @if (session('status') == 'verification-link-sent')
                            {{ __('Reenviar verificación a correo') }}
                        @else
                            {{ __('Enviar verificación a correo') }}
                        @endif
                    </x-button>

                </div>
            </form>

            <div>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf

                    <button type="submit"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 ml-2">
                        {{ __('Salir') }}
                    </button>
                </form>
            </div>
        </div>
    </x-authentication-card>
</x-guest-layout>
