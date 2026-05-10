{{-- Reemplazamos todo el diseño predeterminado con un diseño HTML base limpio --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">
    {{-- CONTENEDOR DE FONDO DE PANTALLA COMPLETA CON LA NUEVA IMAGEN --}}
    {{-- Usamos min-h-screen w-full bg-cover bg-center bg-no-repeat para que la imagen se adapte perfectamente --}}
    <div class="min-h-screen w-full bg-cover bg-center bg-no-repeat flex flex-col justify-center items-center pt-6 sm:pt-0" 
         style="background-image: url('{{ asset('img/background.png') }}');">
        
        {{-- Tarjeta blanca para el formulario (recrea x-authentication-card pero con control total) --}}
        {{-- sm:max-w-md mt-6 px-6 py-4 bg-white shadow-lg overflow-hidden sm:rounded-2xl --}}
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-lg overflow-hidden sm:rounded-2xl">
            
            {{-- Contenedor del logotipo --}}
            <div class="flex justify-center mb-6">
            </div>

            {{-- Título y subtítulo para mejorar la experiencia de usuario --}}
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-gray-900">Crear cuenta</h1>
                <p class="text-gray-500 mt-2">Únete a nosotros. Ingresa tus datos.</p>
            </div>

            <x-validation-errors class="mb-4" />

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div>
                    <x-label for="name" value="{{ __('Name') }}" />
                    {{-- He añadido clases de Tailwind para personalizar los inputs para que coincidan con la paleta --}}
                    {{-- Usamos un fondo beige muy claro y un tinte sutil del azul de marca --}}
                    <x-input id="name" class="block mt-1 w-full border-gray-300 focus:border-[#F9EABC] focus:ring-[#F9EABC] rounded-md shadow-sm bg-[#F9EABC]/10" type="text" name="name" :value="old('name')" required
                        autofocus autocomplete="name" />
                </div>

                <div class="mt-4">
                    <x-label for="role" value="{{ __('Role') }}" />
                    <select id="role" name="role"
                        class="block mt-1 w-full border-gray-300 focus:border-[#F9EABC] focus:ring-[#F9EABC] rounded-md shadow-sm bg-[#F9EABC]/10"
                        autofocus autocomplete="role" aria-placeholder="select role">
                        <option value="" disabled {{ old('role') ? '' : 'selected' }}>
                            {{ __('Seleccione un rol...') }}
                        </option>
                        <option value="rol1" {{ old('role') == 'rol1' ? 'selected' : '' }}>rol1</option>
                        <option value="rol2" {{ old('role') == 'rol2' ? 'selected' : '' }}>rol2</option>
                        <option value="rol3" {{ old('role') == 'rol3' ? 'selected' : '' }}>rol3</option>
                    </select>
                </div>

                <div class="mt-4">
                    <x-label for="email" value="{{ __('Email') }}" />
                    <x-input id="email" class="block mt-1 w-full border-gray-300 focus:border-[#F9EABC] focus:ring-[#F9EABC] rounded-md shadow-sm bg-[#F9EABC]/10" type="email" name="email" :value="old('email')" required
                        autocomplete="username" />
                </div>

                <div class="mt-4">
                    <x-label for="password" value="{{ __('Password') }}" />
                    <x-input id="password" class="block mt-1 w-full border-gray-300 focus:border-[#F9EABC] focus:ring-[#F9EABC] rounded-md shadow-sm bg-[#F9EABC]/10" type="password" name="password" required
                        autocomplete="new-password" />
                </div>

                <div class="mt-4">
                    <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                    <x-input id="password_confirmation" class="block mt-1 w-full border-gray-300 focus:border-[#F9EABC] focus:ring-[#F9EABC] rounded-md shadow-sm bg-[#F9EABC]/10" type="password"
                        name="password_confirmation" required autocomplete="new-password" />
                </div>

                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div class="mt-6">
                        <x-label for="terms">
                            <div class="flex items-center">
                                {{-- Usamos el azul de marca para el checkbox --}}
                                <x-checkbox name="terms" id="terms" class="text-[#16465B] focus:ring-[#16465B]" required />

                                <div class="ms-2 text-sm">
                                    {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="' . route('terms.show') . '" class="underline text-sm text-[#16465B] hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#16465B]">' . __('Terms of Service') . '</a>',
                                        'privacy_policy' => '<a target="_blank" href="' . route('policy.show') . '" class="underline text-sm text-[#16465B] hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#16465B]">' . __('Privacy Policy') . '</a>',
                                    ]) !!}
                                </div>
                            </div>
                        </x-label>
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row items-center justify-between mt-8">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#16465B]"
                        href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>

                    {{-- Usamos el azul de marca (#16465B) para el botón de registro --}}
                    <x-button class="ms-4 w-full justify-center bg-[#16465B] text-white hover:bg-[#16465B]/90 mt-4 sm:mt-0 rounded-xl px-6 py-2.5">
                        {{ __('Register') }}
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>