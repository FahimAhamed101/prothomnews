<?php

use App\Models\Category;
use App\Support\LocaleHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

test('default locale is bn and renders Bengali texts', function () {
    $response = $this->get('/');

    $response->assertOk();
    $response->assertSee('বাংলা');
    $response->assertSee('সর্বশেষ');
    $response->assertSee('ছবি');
    $response->assertSee('ভিডিও');
    $response->assertSee('যোগাযোগ');
});

test('switching language to english stores locale in session and redirects', function () {
    $response = $this->get('/language/en');

    $response->assertRedirect();
    $response->assertSessionHas('locale', 'en');
});

test('switching language to bengali stores locale in session and redirects', function () {
    $response = $this->get('/language/bn');

    $response->assertRedirect();
    $response->assertSessionHas('locale', 'bn');
});

test('switching to unsupported language ignores invalid locale', function () {
    $response = $this->withSession(['locale' => 'bn'])->get('/language/invalid-lang');

    $response->assertRedirect();
    $response->assertSessionMissing('invalid-lang');
});

test('english locale renders english navigation and labels', function () {
    Category::query()->create(['name' => 'রাজনীতি', 'slug' => 'politics']);

    $response = $this->withSession(['locale' => 'en'])->get('/');

    $response->assertOk();
    $response->assertSee('Daily News');
    $response->assertSee('Latest');
    $response->assertSee('Photos');
    $response->assertSee('Videos');
    $response->assertSee('Contact Us');
    $response->assertSee('Politics');
});

test('locale helper converts digits and dates appropriately', function () {
    App::setLocale('bn');
    expect(LocaleHelper::number(12345))->toBe('১২,৩৪৫');
    expect(LocaleHelper::categoryName('রাজনীতি'))->toBe('রাজনীতি');

    App::setLocale('en');
    expect(LocaleHelper::number(12345))->toBe('12,345');
    expect(LocaleHelper::categoryName('রাজনীতি'))->toBe('Politics');

    $dt = Carbon::create(2026, 8, 31, 10, 0, 0);
    App::setLocale('bn');
    expect(LocaleHelper::formatDate($dt, 'd M Y'))->toContain('২০২৬');
    expect(LocaleHelper::headerDate($dt))->toContain('ঢাকা');

    App::setLocale('en');
    expect(LocaleHelper::formatDate($dt, 'd M Y'))->toContain('2026');
    expect(LocaleHelper::headerDate($dt))->toContain('Dhaka');
});
