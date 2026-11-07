<?php

use App\Models\Admin;
use App\Models\Category;
use App\Models\News;
use App\Support\YouTube;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

it('renders the newspaper homepage when there are no articles', function () {
    $this->get(route('user-home'))
        ->assertSuccessful()
        ->assertSee('প্রতিদিন সংবাদ');
});

it('renders published stories and category sections', function () {
    $category = Category::factory()->create(['name' => 'বাংলাদেশ', 'slug' => 'bangladesh']);
    $article = News::factory()->for($category)->create([
        'title' => 'নতুন দিনের প্রধান সংবাদ',
        'top_slider' => true,
    ]);

    $this->get(route('user-home'))
        ->assertSuccessful()
        ->assertSee($article->title)
        ->assertSee($category->name);
});

it('extracts safe youtube ids', function (string $url, ?string $expected) {
    expect(YouTube::videoId($url))->toBe($expected);
})->with([
    'watch URL' => ['https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'dQw4w9WgXcQ'],
    'short URL' => ['https://youtu.be/dQw4w9WgXcQ', 'dQw4w9WgXcQ'],
    'shorts URL' => ['https://youtube.com/shorts/dQw4w9WgXcQ', 'dQw4w9WgXcQ'],
    'untrusted host' => ['https://example.com/watch?v=dQw4w9WgXcQ', null],
]);

it('allows an admin to publish a news story with youtube video', function () {
    Storage::fake('public');
    $admin = Admin::factory()->create();
    $category = Category::factory()->create();

    $response = $this->actingAs($admin, 'admin')->post(route('news-store'), [
        'category_id' => $category->id,
        'title' => 'ভিডিওসহ গুরুত্বপূর্ণ সংবাদ',
        'image' => UploadedFile::fake()->image('cover.jpg', 900, 540),
        'details' => 'সংবাদের বিস্তারিত বিবরণ এখানে থাকবে।',
        'video_type' => 'youtube',
        'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        'status' => '1',
    ]);

    $response->assertRedirect(route('news'));
    $article = News::query()->where('title', 'ভিডিওসহ গুরুত্বপূর্ণ সংবাদ')->firstOrFail();

    expect($article->video_type)->toBe('youtube')
        ->and($article->videoEmbedUrl())->toBe('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ');
});

it('allows an admin to upload a video with a news story', function () {
    Storage::fake('public');
    $admin = Admin::factory()->create();
    $category = Category::factory()->create();

    $this->actingAs($admin, 'admin')->post(route('news-store'), [
        'category_id' => $category->id,
        'title' => 'মাঠ থেকে ভিডিও প্রতিবেদন',
        'image' => UploadedFile::fake()->image('cover.jpg', 900, 540),
        'details' => 'ভিডিও প্রতিবেদনের বিস্তারিত বিবরণ।',
        'video_type' => 'upload',
        'video_file' => UploadedFile::fake()->create('report.mp4', 1024, 'video/mp4'),
        'status' => '1',
    ])->assertRedirect(route('news'));

    $article = News::query()->where('title', 'মাঠ থেকে ভিডিও প্রতিবেদন')->firstOrFail();
    Storage::disk('public')->assertExists(Str::after($article->video_path, 'storage/'));
});

it('rejects a non youtube host for youtube news', function () {
    $admin = Admin::factory()->create();
    $category = Category::factory()->create();

    $this->actingAs($admin, 'admin')->from(route('news-create'))->post(route('news-store'), [
        'category_id' => $category->id,
        'title' => 'Invalid video story',
        'image' => UploadedFile::fake()->image('cover.jpg'),
        'details' => 'Story details.',
        'video_type' => 'youtube',
        'video_url' => 'https://example.com/watch?v=dQw4w9WgXcQ',
    ])->assertRedirect(route('news-create'))->assertSessionHasErrors('video_url');
});
