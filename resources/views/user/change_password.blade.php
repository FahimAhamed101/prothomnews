@extends('user.body.app')
@section('title', __('Change Password') . ' | ' . __('Daily News'))
@section('content')
<div class="page-shell py-12">
    <div class="mx-auto max-w-lg">
        <h1 class="section-heading mb-7">{{ __('Change Password') }}</h1>
        @if (session('status'))
            <div class="mb-5 bg-green-50 p-3 text-sm text-green-700">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-5 bg-red-50 p-3 text-sm text-red-700">{{ $errors->first() }}</div>
        @endif
        <form action="{{ route('user-change-password-update') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="mb-2 block font-semibold text-sm">{{ __('Old Password') }}</label>
                <input type="password" name="old_password" class="field" required>
            </div>
            <div>
                <label class="mb-2 block font-semibold text-sm">{{ __('New Password') }}</label>
                <input type="password" name="new_password" class="field" required>
            </div>
            <div>
                <label class="mb-2 block font-semibold text-sm">{{ __('Confirm Password') }}</label>
                <input type="password" name="new_password_confirmation" class="field" required>
            </div>
            <button class="button-primary">{{ __('Update Password') }}</button>
        </form>
    </div>
</div>
@endsection
