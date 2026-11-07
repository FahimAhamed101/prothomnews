<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SaveNewsRequest;
use App\Models\Banner;
use App\Models\Category;
use App\Models\ContactUs;
use App\Models\News;
use App\Models\PhotoGallery;
use App\Models\Review;
use App\Models\Seo;
use App\Models\Subcategory;
use App\Models\User;
use App\Models\VideoGallery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ContentController extends Controller
{
    public function categories(): View
    {
        return view('admin.content.categories.index', ['categories' => Category::latest()->get()]);
    }

    public function createCategory(): View
    {
        return view('admin.content.categories.form', ['category' => null]);
    }

    public function storeCategory(Request $request): RedirectResponse
    {
        $validated = $request->validate(['name' => ['required', 'string', 'max:255']]);
        Category::create(['name' => $validated['name'], 'slug' => Str::slug($validated['name'])]);

        return to_route('category')->with($this->notification('Category created successfully.'));
    }

    public function editCategory(Category $category): View
    {
        return view('admin.content.categories.form', compact('category'));
    }

    public function updateCategory(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate(['name' => ['required', 'string', 'max:255']]);
        $category->update(['name' => $validated['name'], 'slug' => Str::slug($validated['name'])]);

        return to_route('category')->with($this->notification('Category updated successfully.'));
    }

    public function deleteCategory(Category $category): RedirectResponse
    {
        $category->delete();

        return back()->with($this->notification('Category deleted successfully.'));
    }

    public function subcategories(): View
    {
        return view('admin.content.subcategories.index', ['subcategories' => Subcategory::with('category')->latest()->get()]);
    }

    public function createSubcategory(): View
    {
        return view('admin.content.subcategories.form', ['subcategory' => null, 'categories' => Category::orderBy('name')->get()]);
    }

    public function storeSubcategory(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        Subcategory::create([...$validated, 'slug' => Str::slug($validated['name'])]);

        return to_route('subcategory')->with($this->notification('Subcategory created successfully.'));
    }

    public function editSubcategory(Subcategory $subcategory): View
    {
        return view('admin.content.subcategories.form', ['subcategory' => $subcategory, 'categories' => Category::orderBy('name')->get()]);
    }

    public function updateSubcategory(Request $request, Subcategory $subcategory): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        $subcategory->update([...$validated, 'slug' => Str::slug($validated['name'])]);

        return to_route('subcategory')->with($this->notification('Subcategory updated successfully.'));
    }

    public function deleteSubcategory(Subcategory $subcategory): RedirectResponse
    {
        $subcategory->delete();

        return back()->with($this->notification('Subcategory deleted successfully.'));
    }

    public function news(): View
    {
        return view('admin.content.news.index', ['newsItems' => News::with(['category', 'subcategory', 'admin'])->latest()->get()]);
    }

    public function createNews(): View
    {
        return view('admin.content.news.form', $this->newsFormData());
    }

    public function storeNews(SaveNewsRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['admin_id'] = Auth::guard('admin')->id();
        $validated['slug'] = Str::slug($validated['title']);
        $validated['image'] = $this->storeImage($request, 'image', 'storage/news');
        $this->storeNewsVideo($validated, $request);
        $validated['post_date'] = now()->format('d-m-Y');
        $validated['post_month'] = now()->format('F');
        $this->applyNewsFlags($validated, $request);

        News::create($validated);

        return to_route('news')->with($this->notification('News article created successfully.'));
    }

    public function editNews(News $news): View
    {
        return view('admin.content.news.form', [...$this->newsFormData(), 'news' => $news]);
    }

    public function updateNews(SaveNewsRequest $request, News $news): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['title']);
        $this->applyNewsFlags($validated, $request);
        $this->storeNewsVideo($validated, $request, $news);

        if ($image = $this->storeImage($request, 'image', 'storage/news')) {
            $validated['image'] = $image;
        }

        $news->update($validated);

        return to_route('news')->with($this->notification('News article updated successfully.'));
    }

    public function deleteNews(News $news): RedirectResponse
    {
        $news->delete();

        return back()->with($this->notification('News article deleted successfully.'));
    }

    public function toggleNews(News $news): RedirectResponse
    {
        $news->update(['status' => ! $news->status]);

        return back()->with($this->notification('News status updated successfully.'));
    }

    public function banner(): View
    {
        return view('admin.content.banner', ['banner' => Banner::firstOrCreate(['id' => 1])]);
    }

    public function updateBanner(Request $request): RedirectResponse
    {
        $banner = Banner::firstOrCreate(['id' => 1]);
        $data = $request->validate([
            'home_one' => ['nullable', 'string', 'max:255'],
            'home_two' => ['nullable', 'string', 'max:255'],
            'home_three' => ['nullable', 'string', 'max:255'],
            'home_four' => ['nullable', 'string', 'max:255'],
            'news_category_one' => ['nullable', 'string', 'max:255'],
            'news_details_one' => ['nullable', 'string', 'max:255'],
        ]);
        $banner->update($data);

        return back()->with($this->notification('Banners updated successfully.'));
    }

    public function seo(): View
    {
        return view('admin.content.seo', ['seo' => Seo::firstOrCreate(['id' => 1])]);
    }

    public function updateSeo(Request $request): RedirectResponse
    {
        Seo::firstOrCreate(['id' => 1])->update($request->validate([
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_author' => ['nullable', 'string', 'max:255'],
            'meta_keyword' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:255'],
        ]));

        return back()->with($this->notification('SEO settings updated successfully.'));
    }

    public function photos(): View
    {
        return view('admin.content.photos.index', ['photos' => PhotoGallery::latest()->get()]);
    }

    public function createPhoto(): View
    {
        return view('admin.content.photos.form');
    }

    public function storePhoto(Request $request): RedirectResponse
    {
        $request->validate(['photo_gallery' => ['required', 'string', 'max:255']]);
        PhotoGallery::create(['photo_gallery' => $request->photo_gallery, 'post_date' => now()->format('d-m-Y')]);

        return to_route('photo')->with($this->notification('Photo added successfully.'));
    }

    public function deletePhoto(PhotoGallery $photo): RedirectResponse
    {
        $photo->delete();

        return back()->with($this->notification('Photo deleted successfully.'));
    }

    public function videos(): View
    {
        return view('admin.content.videos.index', ['videos' => VideoGallery::latest()->get()]);
    }

    public function createVideo(): View
    {
        return view('admin.content.videos.form');
    }

    public function storeVideo(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'image' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'max:255'],
        ]);

        VideoGallery::create([...$validated, 'post_date' => now()->format('d-m-Y')]);

        return to_route('video')->with($this->notification('Video added successfully.'));
    }

    public function deleteVideo(VideoGallery $video): RedirectResponse
    {
        $video->delete();

        return back()->with($this->notification('Video deleted successfully.'));
    }

    public function pendingReviews(): View
    {
        return view('admin.content.reviews', ['reviews' => Review::with(['user', 'news'])->where('status', false)->latest()->get(), 'title' => 'Pending Reviews']);
    }

    public function approvedReviews(): View
    {
        return view('admin.content.reviews', ['reviews' => Review::with(['user', 'news'])->where('status', true)->latest()->get(), 'title' => 'Approved Reviews']);
    }

    public function approveReview(Review $review): RedirectResponse
    {
        $review->update(['status' => true]);

        return back()->with($this->notification('Review approved successfully.'));
    }

    public function deleteReview(Review $review): RedirectResponse
    {
        $review->delete();

        return back()->with($this->notification('Review deleted successfully.'));
    }

    public function users(): View
    {
        return view('admin.content.users', ['users' => User::latest()->get()]);
    }

    public function contacts(): View
    {
        return view('admin.content.contacts', ['contacts' => ContactUs::latest()->get()]);
    }

    private function newsFormData(): array
    {
        return [
            'news' => null,
            'categories' => Category::orderBy('name')->get(),
            'subcategories' => Subcategory::orderBy('name')->get(),
        ];
    }

    private function storeImage(Request $request, string $field, string $directory): ?string
    {
        if (! $request->hasFile($field)) {
            return null;
        }

        $storageDirectory = Str::after($directory, 'storage/');
        $path = $request->file($field)->store($storageDirectory, 'public');

        return 'storage/'.$path;
    }

    /**
     * @param  array<string, mixed>  $validated
     */
    private function applyNewsFlags(array &$validated, Request $request): void
    {
        foreach (['status', 'breaking_news', 'top_slider', 'first_section_three', 'first_section_nine'] as $field) {
            $validated[$field] = $request->boolean($field);
        }
    }

    /**
     * @param  array<string, mixed>  $validated
     */
    private function storeNewsVideo(array &$validated, SaveNewsRequest $request, ?News $news = null): void
    {
        $videoType = $validated['video_type'] ?? null;

        if ($videoType !== 'upload') {
            unset($validated['video_file']);
            $validated['video_path'] = null;

            if ($news?->video_path) {
                Storage::disk('public')->delete(Str::after($news->video_path, 'storage/'));
            }
        }

        if ($videoType !== 'youtube') {
            $validated['video_url'] = null;
        }

        if ($videoType === 'upload' && $request->hasFile('video_file')) {
            if ($news?->video_path) {
                Storage::disk('public')->delete(Str::after($news->video_path, 'storage/'));
            }

            $validated['video_path'] = 'storage/'.$request->file('video_file')->store('news/videos', 'public');
        } elseif ($videoType === 'upload' && $news?->video_path) {
            $validated['video_path'] = $news->video_path;
        }

        unset($validated['video_file']);
    }

    private function notification(string $message, string $type = 'success'): array
    {
        return ['message' => $message, 'alert-type' => $type];
    }
}
