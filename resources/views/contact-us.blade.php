@extends('user.body.app')
@section('title', __('Contact Us') . ' | ' . __('Daily News'))
@section('content')
    <div class="page-shell py-12">
        <div class="mx-auto max-w-2xl">
            <h1 class="section-heading mb-3">{{ __('Contact Us') }}</h1>
            <p class="mb-8 leading-7 text-gray-600">{{ __('Use the form below to send news, feedback, or corrections.') }}</p>
            @if ($errors->any())
                <div class="mb-5 bg-red-50 p-4 text-sm text-red-700">{{ $errors->first() }}</div>
            @endif
            <form method="POST" action="{{ route('contact-us-post') }}" class="space-y-5">
                @csrf
                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block font-semibold">{{ __('Name') }}</label>
                        <input name="name" value="{{ old('name') }}" class="field" required>
                    </div>
                    <div>
                        <label class="mb-2 block font-semibold">{{ __('Email') }}</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="field" required>
                    </div>
                </div>
                <div>
                    <label class="mb-2 block font-semibold">{{ __('Phone') }}</label>
                    <input name="phone" value="{{ old('phone') }}" class="field">
                </div>
                <div>
                    <label class="mb-2 block font-semibold">{{ __('Message') }}</label>
                    <textarea name="comments" rows="6" class="field" required>{{ old('comments') }}</textarea>
                </div>
                <button class="button-primary">{{ __('Send Message') }}</button>
            </form>
        </div>
    </div>
@endsection
