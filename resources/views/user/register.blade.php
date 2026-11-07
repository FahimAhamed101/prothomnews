@extends('user.body.app')
@section('title', __('Register') . ' | ' . __('Daily News'))
@section('content')
<div class="page-shell py-14">
    <div class="mx-auto max-w-md border border-gray-200 p-6 sm:p-9 bg-white shadow-xs">
        <h1 class="text-3xl font-extrabold">{{ __('Create Account') }}</h1>
        @if ($errors->any())
            <div class="mt-5 bg-red-50 p-3 text-sm text-red-700">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('user-register-post') }}" class="mt-7 space-y-5">
            @csrf
            <div>
                <label class="mb-2 block font-semibold text-sm">{{ __('Name') }}</label>
                <input name="name" value="{{ old('name') }}" class="field" required>
            </div>
            <div>
                <label class="mb-2 block font-semibold text-sm">{{ __('Email') }}</label>
                <input type="email" name="email" value="{{ old('email') }}" class="field" required>
            </div>
            <div>
                <label class="mb-2 block font-semibold text-sm">{{ __('Password') }}</label>
                <input type="password" name="password" class="field" required>
            </div>
            <div>
                <label class="mb-2 block font-semibold text-sm">{{ __('Confirm Password') }}</label>
                <input type="password" name="password_confirmation" class="field" required>
            </div>
            <button class="button-primary w-full">{{ __('Register') }}</button>
        </form>
    </div>
</div>
@endsection
