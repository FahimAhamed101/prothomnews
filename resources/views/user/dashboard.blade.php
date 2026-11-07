@extends('user.body.app')
@section('title', __('My Profile') . ' | ' . __('Daily News'))
@section('content')
<div class="page-shell py-12">
    <div class="mx-auto grid max-w-4xl gap-8 md:grid-cols-[220px_1fr]">
        <aside class="h-fit bg-news-paper p-5 border border-gray-200">
            <h2 class="text-xl font-bold">{{ $data->name }}</h2>
            <div class="mt-5 space-y-3 text-sm font-semibold">
                <a class="block text-news-red" href="{{ route('user-dashboard') }}">{{ __('Profile') }}</a>
                <a class="block text-gray-700 hover:text-news-red" href="{{ route('user-change-password') }}">{{ __('Change Password') }}</a>
                <a class="block text-gray-700 hover:text-news-red" href="{{ route('user-logout') }}">{{ __('Logout') }}</a>
            </div>
        </aside>
        <section>
            <h1 class="section-heading mb-7">{{ __('My Profile') }}</h1>
            @if ($errors->any())
                <div class="mb-5 bg-red-50 p-3 text-sm text-red-700">{{ $errors->first() }}</div>
            @endif
            <form method="POST" action="{{ route('user-profile-update') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <div>
                    <label class="mb-2 block font-semibold text-sm">{{ __('Name') }}</label>
                    <input name="name" value="{{ old('name', $data->name) }}" class="field" required>
                </div>
                <div>
                    <label class="mb-2 block font-semibold text-sm">{{ __('Email') }}</label>
                    <input type="email" name="email" value="{{ old('email', $data->email) }}" class="field" required>
                </div>
                <div>
                    <label class="mb-2 block font-semibold text-sm">{{ __('Phone') }}</label>
                    <input name="phone" value="{{ old('phone', $data->phone) }}" class="field">
                </div>
                <div>
                    <label class="mb-2 block font-semibold text-sm">{{ __('Photo') }}</label>
                    <input type="file" name="image" accept="image/*" class="field">
                </div>
                <button class="button-primary">{{ __('Save Changes') }}</button>
            </form>
        </section>
    </div>
</div>
@endsection
