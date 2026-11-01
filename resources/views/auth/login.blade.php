@section('title','| Login')

<x-authentication-layout>
    <h1 class="text-3xl text-dafault dark:text-gray-100 font-bold mb-6">
        Digital Records Management System
    </h1>
    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif   
    <!-- Form -->
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="space-y-4">
            <div>
                <x-label for="email" value="{{ __('Email Address') }}" />
                <x-input id="email" type="email" name="email" :value="old('email')" required autofocus />                
            </div>
            <div>
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" type="password" name="password" required autocomplete="current-password" />                
            </div>
        </div>
        <div class="flex items-center justify-between mt-6">
            @if (Route::has('password.request'))
                <div class="mr-1">
                    <a class="text-gray3 text-sm underline hover:no-underline" href="{{ route('password.request') }}">
                        {{ __('Forgot Password?') }}
                    </a>
                </div>
            @endif            
            <x-button class="text-white ml-3 bg-secondary/90 hover:bg-secondary">
                {{ __('Login') }}
            </x-button>            
        </div>
    </form>

    @if (config('app.demo_login_enabled'))
        <div class="my-5 flex items-center gap-3" aria-hidden="true">
            <div class="h-px flex-1 bg-gray-200 dark:bg-gray-700"></div>
            <span class="text-xs uppercase text-gray-500">{{ __('or') }}</span>
            <div class="h-px flex-1 bg-gray-200 dark:bg-gray-700"></div>
        </div>
        <form method="POST" action="{{ route('demo.login') }}">
            @csrf
            <button type="submit" class="w-full rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                {{ __('Continue as Demo Admin') }}
            </button>
            <!-- <p class="mt-2 text-center text-xs text-gray-500">{{ __('Uses a shared demo account. Changes may be visible to other visitors.') }}</p> -->
        </form>
    @endif

    <x-validation-errors class="mt-4" />
    <!-- Footer -->
    <div class="pt-5 mt-6 border-t border-gray-100 dark:border-gray-700/60">
        <!-- <div class="text-sm">
            {{ __('Don\'t you have an account?') }} <a class="font-medium text-violet-500 hover:text-violet-600 dark:hover:text-violet-400" href="{{ route('register') }}">{{ __('Sign Up') }}</a>
        </div> -->
        <!-- Warning -->
        <div class="mt-5">
            <div class="bg-yellow-500/20 text-yellow-700 px-3 py-2 rounded-lg dark:text-yellow-400">
                <svg class="inline w-3 h-3 shrink-0 fill-current" viewBox="0 0 12 12">
                    <path d="M10.28 1.28L3.989 7.575 1.695 5.28A1 1 0 00.28 6.695l3 3a1 1 0 001.414 0l7-7A1 1 0 0010.28 1.28z" />
                </svg>
                <span class="text-sm">
                    Coordinate with the IT Department to obtain your account credentials.
                </span>
            </div>
        </div>
    </div>
</x-authentication-layout>
