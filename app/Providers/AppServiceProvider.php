<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\News;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('user.body.app', function ($view): void {
            $view->with([
                'navigationCategories' => Category::query()
                    ->with('subcategories:id,category_id,name,slug')
                    ->orderBy('id')
                    ->get(),
                'breakingNews' => News::query()
                    ->published()
                    ->where('breaking_news', true)
                    ->latest()
                    ->limit(8)
                    ->get(),
            ]);
        });
    }
}
