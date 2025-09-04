@section('title','| Reset Password')

<x-authentication-layout>
    <h1 class="text-xl text-default dark:text-gray-100 font-bold mb-6">{{ __('Reset your Password') }}</h1>
    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif
    <!-- Form -->
    <form method="POST" action="">
        @csrf
        <div>
            <x-label for="email">{{ __('Email Address') }} <span class="text-red-500">*</span></x-label>
            <x-input id="email" type="email" name="email" :value="old('email')" required autofocus />                
        </div>
        <div class="flex items-center justify-between mt-6">
            <div class="mr-1">
                <a class="text-gray3 text-sm underline hover:no-underline" href="{{ route('login') }}">
                    {{ __('Already Have An Account?') }}
                </a>
            </div>          
            <x-button class="text-white ml-3 bg-secondary/90 hover:bg-secondary">
                {{ __('Send Reset Link') }}
            </x-button>            
        </div>
    </form>
    <x-validation-errors class="mt-4" /> 
</x-authentication-layout>
