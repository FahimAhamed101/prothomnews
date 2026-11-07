@extends('admin.layout.app')

@section('content')
    <div class="content"><div class="container-fluid">
        <div class="page-title-box"><h4 class="page-title">{{ $news ? 'Edit News' : 'Add News' }}</h4></div>
        <div class="card"><div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" enctype="multipart/form-data" action="{{ $news ? route('news-update', $news->id) : route('news-store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3"><label class="form-label">Category</label><select class="form-control" name="category_id" required>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $news?->category_id) == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select></div>
                    <div class="col-md-6 mb-3"><label class="form-label">Subcategory</label><select class="form-control" name="subcategory_id">
                        @foreach ($subcategories as $subcategory)
                            <option value="{{ $subcategory->id }}" @selected(old('subcategory_id', $news?->subcategory_id) == $subcategory->id)>{{ $subcategory->name }}</option>
                        @endforeach
                    </select></div>
                </div>
                <div class="mb-3"><label class="form-label">Title</label><input class="form-control" name="title" value="{{ old('title', $news?->title) }}" required></div>
                <div class="mb-3"><label class="form-label">Cover image</label><input class="form-control" type="file" name="image" accept="image/jpeg,image/png,image/webp" @required(! $news)>@if ($news?->image)<small>Current: {{ $news->image }}</small>@endif</div>
                <div class="mb-3"><label class="form-label">Details</label><textarea class="form-control" name="details" rows="8">{{ old('details', $news?->details) }}</textarea></div>
                <div class="mb-3"><label class="form-label">Tags</label><input class="form-control" name="tags" value="{{ old('tags', $news?->tags) }}"></div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Article video</label>
                        <select class="form-control" name="video_type" id="video_type">
                            <option value="">No video</option>
                            <option value="youtube" @selected(old('video_type', $news?->video_type) === 'youtube')>YouTube URL</option>
                            <option value="upload" @selected(old('video_type', $news?->video_type) === 'upload')>Upload video</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">YouTube URL</label>
                        <input class="form-control" type="url" name="video_url" placeholder="https://www.youtube.com/watch?v=..." value="{{ old('video_url', $news?->video_url) }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Video file (max 100 MB)</label>
                        <input class="form-control" type="file" name="video_file" accept="video/mp4,video/webm,video/quicktime">
                        @if ($news?->video_path)<small>Current: {{ $news->video_path }}</small>@endif
                    </div>
                </div>
                <div class="row">
                    @foreach (['status' => 'Published', 'breaking_news' => 'Breaking', 'top_slider' => 'Top Slider', 'first_section_three' => 'Section Three', 'first_section_nine' => 'Section Nine'] as $field => $label)
                        <div class="col-md-2 mb-3"><label><input type="checkbox" name="{{ $field }}" value="1" @checked(old($field, $news?->{$field}))> {{ $label }}</label></div>
                    @endforeach
                </div>
                <button class="btn btn-primary">Save</button>
                <a href="{{ route('news') }}" class="btn btn-light">Cancel</a>
            </form>
        </div></div>
    </div></div>
@endsection
