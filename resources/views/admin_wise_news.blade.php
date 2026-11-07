@extends('user.body.app')
@section('title', __('News by :admin', ['admin' => $admin->name]) . ' | ' . __('Daily News'))
@section('content')
    <div class="page-shell py-10">
        <h1 class="section-heading mb-8">{{ __('News by :admin', ['admin' => $admin->name]) }}</h1>
        @include('partials.news-list', ['newsItems' => $news])
    </div>
@endsection
