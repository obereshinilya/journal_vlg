{{--<x-guest-layout>--}}
{{--    <x-auth-card>--}}
{{--        <x-slot name="logo">--}}
{{--            <a href="/">--}}
{{--                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />--}}
{{--            </a>--}}
{{--        </x-slot>--}}

{{--        <!-- Session Status -->--}}
{{--        <x-auth-session-status class="mb-4" :status="session('status')" />--}}

{{--        <!-- Validation Errors -->--}}
{{--        <x-auth-validation-errors class="mb-4" :errors="$errors" />--}}

{{--        <form method="POST" action="{{ route('login') }}">--}}
{{--            @csrf--}}

{{--            <!-- Email Address -->--}}
{{--            <div>--}}
{{--                <x-label for="email" :value="__('Email')" />--}}

{{--                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />--}}
{{--            </div>--}}

{{--            <!-- Password -->--}}
{{--            <div class="mt-4">--}}
{{--                <x-label for="password" :value="__('Password')" />--}}

{{--                <x-input id="password" class="block mt-1 w-full"--}}
{{--                                type="password"--}}
{{--                                name="password"--}}
{{--                                required autocomplete="current-password" />--}}
{{--            </div>--}}

{{--            <!-- Remember Me -->--}}
{{--            <div class="block mt-4">--}}
{{--                <label for="remember_me" class="inline-flex items-center">--}}
{{--                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">--}}
{{--                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>--}}
{{--                </label>--}}
{{--            </div>--}}

{{--            <div class="flex items-center justify-end mt-4">--}}
{{--                @if (Route::has('password.request'))--}}
{{--                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">--}}
{{--                        {{ __('Forgot your password?') }}--}}
{{--                    </a>--}}
{{--                @endif--}}

{{--                <x-button class="ml-3">--}}
{{--                    {{ __('Log in') }}--}}
{{--                </x-button>--}}
{{--            </div>--}}
{{--        </form>--}}
{{--    </x-auth-card>--}}
{{--</x-guest-layout>--}}
@extends('layouts.app')
@section('title')
    Логин
@endsection
@section('content')
    <script src="{{asset('assets/classes/small-notification/small-notification.js')}}"></script>
    <link rel="stylesheet" href="{{asset('assets/classes/small-notification/small-notification.css')}}">
    <div class="login-container">
        <div class="login p-t-50 p-b-90">

            <form class="login-form validate-form flex-sb flex-w" method="POST" action="{{ route('login') }}">
                @csrf

                <span class="login-form-title p-b-51">
					<h5>Войти</h5>
                </span>

                @error('name')
                <script>
                    new SmallNotification('Ошибка', '{{ $message }}', '#ea6a6a')
                </script>
                @enderror
                <div class="grey-big-input-block validate-input m-b-16" data-validate = "Username is required">
                    <input class="grey-big-input @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Логин">

                </div>


                @error('password')
                <script>
                    new SmallNotification('Ошибка', '{{ $message }}', '#ea6a6a')
                </script>
                @enderror
                <div class="grey-big-input-block validate-input m-b-16" data-validate = "Password is required">
                    <input class="grey-big-input @error('password') is-invalid @enderror" type="password" name="password" placeholder="Пароль" required autocomplete="current-password">

                </div>


                <div class="big-blue-button-block m-t-17">
                    <button class="big-blue-button">
                        Войти
                    </button>
                </div>
            </form>
        </div>
    </div>


@endsection
