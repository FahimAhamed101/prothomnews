@extends('user.body.app')
@section('title', __('Login') . ' | ' . __('Daily News'))
@section('content')
<div class="page-shell py-14">
    <div class="mx-auto max-w-md border border-gray-200 p-6 sm:p-9 bg-white shadow-xs">
        <h1 class="text-3xl font-extrabold">{{ __('Login') }}</h1>
        <p class="mt-2 text-sm text-gray-500">{{ __('Sign in to comment and manage your profile.') }}</p>
        @if ($errors->any())
            <div class="mt-5 bg-red-50 p-3 text-sm text-red-700">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('user-login-post') }}" class="mt-7 space-y-5">
            @csrf
            <div>
                <label class="mb-2 block font-semibold text-sm">{{ __('Email') }}</label>
                <input type="email" name="email" value="{{ old('email') }}" class="field" required autofocus>
            </div>
            <div>
                <label class="mb-2 block font-semibold text-sm">{{ __('Password') }}</label>
                <input type="password" name="password" class="field" required>
            </div>
            <button class="button-primary w-full">{{ __('Login') }}</button>
        </form>
        <p class="mt-5 text-center text-sm">
            {{ __('New reader?') }} <a href="{{ route('user-register') }}" class="font-semibold text-news-red hover:underline">{{ __('Create an account') }}</a>
        </p>
    </div>
</div>
@endsection
