<x-guest-layout>
    <div class="flex h-screen w-full overflow-hidden bg-white">
        
        <div class="hidden lg:flex lg:w-1/2 p-4 h-full"> 
            <div class="w-full h-full bg-[#F1F8E9] rounded-[2.5rem] flex items-center justify-center p-8">
                <div class="image-wrapper flex items-center justify-center w-full h-full">
                    <img src="{{ asset('img/image.png') }}" 
                         class="scaled-image max-h-[80vh] w-auto object-contain" 
                         alt="Login Illustration">
                </div>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex flex-col justify-center items-center bg-white px-8 py-12 h-full overflow-y-auto">
            <div class="w-full max-w-md">
                
                <div class="mb-8 text-center">
                    <h1 class="text-3xl font-bold text-gray-900">Hi!! Welcome back</h1>
                    <p class="text-gray-500 mt-2">We started, Please enter your details.</p>
                </div>

                <x-validation-errors class="mb-4" />

                @session('status')
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ $value }}
                    </div>
                @endsession

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div>
                        <x-label for="email" value="{{ __('Email') }}" />
                        <x-input id="email" class="block mt-1 w-full border-none bg-[#F1F8E9] focus:bg-gray-200 focus:ring-0" type="email" name="email" required autofocus />
                    </div>

                    <div class="mt-4">
                        <x-label for="password" value="{{ __('Password') }}" />
                        <x-input id="password" class="block mt-1 w-full border-none bg-[#F1F8E9] focus:bg-gray-200 focus:ring-0 " type="password" name="password" required />
                    </div>

                    <div class="flex items-center justify-between mt-6">
                        <label class="flex items-center">
                            <x-checkbox name="remember" style="background-color: #d1f5a7;" />
                            <span class="ms-2 text-sm text-gray-600">Remember me</span>
                        </label>
                        <a class="text-sm text-gray-900 font-semibold" href="{{ route('password.request') }}">Forgot?</a>
                    </div>

                    <x-button class="w-full justify-center mt-8 bg-black">Log in</x-button>
                </form>
                
                <p class="text-center text-sm text-gray-600 mt-8">
                    Don't have an account? <a href="{{ route('register') }}" class="font-bold text-gray-900">Register here</a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>