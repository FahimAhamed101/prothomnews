<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Banner;
use App\Models\Category;
use App\Models\ContactUs;
use App\Models\News;
use App\Models\PhotoGallery;
use App\Models\Review;
use App\Models\Subcategory;
use App\Models\VideoGallery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $latestNews = News::query()
            ->published()
            ->with(['category:id,name,slug', 'admin:id,name'])
            ->latest()
            ->limit(40)
            ->get();

        $hero = $latestNews->firstWhere('top_slider', true) ?? $latestNews->first();
        $leadStories = $latestNews->where('id', '!=', $hero?->id)->take(4);
        $popularNews = News::query()->published()->with('category:id,name,slug')->orderByDesc('view_count')->limit(6)->get();
        $videoNews = News::query()->published()->whereNotNull('video_type')->latest()->limit(5)->get();

        $categorySections = Category::query()
            ->with([
                'subcategories:id,category_id,name,slug',
                'news' => fn ($query) => $query->published()->latest()->limit(5),
            ])
            ->orderBy('id')
            ->get()
            ->filter(fn (Category $category) => $category->news->isNotEmpty());

        return view('welcome', [
            'hero' => $hero,
            'leadStories' => $leadStories,
            'latestNews' => $latestNews,
            'popularNews' => $popularNews,
            'categorySections' => $categorySections,
            'videoNews' => $videoNews,
            'videoGalleries' => VideoGallery::query()->latest()->limit(5)->get(),
            'photoGalleries' => PhotoGallery::query()->latest()->limit(5)->get(),
            'banner' => Banner::query()->find(1),
        ]);
    }

    public function newsDetais(int $id, string $slug): View
    {
        $news = News::query()->published()->with(['category', 'subcategory', 'admin'])->findOrFail($id);

        $newsKey = 'news-view-'.$news->id;
        if (! Session::has($newsKey)) {
            $news->increment('view_count');
            Session::put($newsKey, true);
        }

        return view('news_details', [
            'news' => $news,
            'tags_all' => array_filter(array_map('trim', explode(',', (string) $news->tags))),
            'relatedNews' => News::query()->published()->where('category_id', $news->category_id)->whereKeyNot($news)->latest()->limit(6)->get(),
            'newnewspost' => News::query()->published()->latest()->limit(8)->get(),
            'newspopular' => News::query()->published()->orderByDesc('view_count')->limit(8)->get(),
            'banner' => Banner::query()->find(1),
        ]);
    }

    public function categoryNews(int $id, string $slug): View
    {
        $category = Category::query()->findOrFail($id);
        $news = News::query()->published()->whereBelongsTo($category)->latest()->paginate(12);

        return view('category_news', [
            'categoryName' => $category,
            'recentNews' => $news->first(),
            'relatedTwoNews' => $news->slice(1, 2),
            'otherNews' => $news,
            'newnewspost' => News::query()->published()->latest()->limit(8)->get(),
            'newspopular' => News::query()->published()->orderByDesc('view_count')->limit(8)->get(),
            'banner' => Banner::query()->find(1),
        ]);
    }

    public function subCategoryNews(int $id, string $slug): View
    {
        $subcategory = Subcategory::query()->findOrFail($id);
        $news = News::query()->published()->whereBelongsTo($subcategory)->latest()->paginate(12);

        return view('sub_category_news', [
            'subCategoryName' => $subcategory,
            'recentNews' => $news->first(),
            'relatedTwoNews' => $news->slice(1, 2),
            'otherNews' => $news,
            'newnewspost' => News::query()->published()->latest()->limit(8)->get(),
            'newspopular' => News::query()->published()->orderByDesc('view_count')->limit(8)->get(),
        ]);
    }

    public function searchByDate(Request $request): View
    {
        $validated = $request->validate(['date' => ['required', 'date']]);
        $formatDate = date('d-m-Y', strtotime($validated['date']));

        return view('search_by_date', [
            'news' => News::query()->published()->where('post_date', $formatDate)->latest()->get(),
            'formatDate' => $formatDate,
        ]);
    }

    public function searchByName(Request $request): View
    {
        $validated = $request->validate(['search' => ['required', 'string', 'max:100']]);
        $item = $validated['search'];

        return view('search_by_name', [
            'news' => News::query()->published()->where('title', 'like', '%'.$item.'%')->latest()->get(),
            'item' => $item,
        ]);
    }

    public function adminNews(int $id): View
    {
        $admin = Admin::query()->findOrFail($id);

        return view('admin_wise_news', ['admin' => $admin, 'news' => News::query()->published()->whereBelongsTo($admin)->latest()->get()]);
    }

    public function reviewPost(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id' => ['required', 'exists:news,id'],
            'comments' => ['required', 'string', 'max:2000'],
        ]);

        Review::query()->create([
            'user_id' => Auth::guard('web')->id(),
            'news_id' => $validated['id'],
            'comments' => $validated['comments'],
        ]);

        return back()->with(['message' => 'Review will be published after approval.', 'alert-type' => 'success']);
    }

    public function allPhotoGallery(): View
    {
        return view('photo_gallery', ['photogalleries' => PhotoGallery::query()->latest()->get()]);
    }

    public function allVideoGallery(): View
    {
        return view('video_gallery', ['videogalleries' => VideoGallery::query()->latest()->get()]);
    }

    public function contactUs(): View
    {
        return view('contact-us');
    }

    public function contactUsPost(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'comments' => ['required', 'string', 'max:3000'],
        ]);

        ContactUs::query()->create($validated);

        return back()->with(['message' => 'Your message has been sent.', 'alert-type' => 'success']);
    }
}
