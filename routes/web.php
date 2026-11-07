<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\LiveTvController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/language/{locale}', [LocaleController::class, 'switch'])->name('language.switch');

Route::get('/', [HomeController::class, 'index'])->name('user-home');
Route::get('/login', fn () => redirect()->route('user-login'))->name('login');

Route::get('/news/{id}/{slug}', [HomeController::class, 'newsDetais'])->name('news-detail');
Route::get('/category/{id}/{slug}', [HomeController::class, 'categoryNews'])->name('category-news');
Route::get('/subcategory/{id}/{slug}', [HomeController::class, 'subCategoryNews'])->name('sub-category-news');
Route::post('/search/date', [HomeController::class, 'searchByDate'])->name('search-by-date');
Route::post('/search/name', [HomeController::class, 'searchByName'])->name('search-by-name');
Route::get('/admin/news/{id}', [HomeController::class, 'adminNews'])->whereNumber('id')->name('admin-wise-news');
Route::get('/photo-gallery', [HomeController::class, 'allPhotoGallery'])->name('all-photo-gallery');
Route::get('/video-gallery', [HomeController::class, 'allVideoGallery'])->name('all-video-gallery');
Route::get('/contact-us', [HomeController::class, 'contactUs'])->name('contact-us');
Route::post('/contact-us', [HomeController::class, 'contactUsPost'])->name('contact-us-post');

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'login'])->name('admin-login');
    Route::post('/login/post', [AdminController::class, 'loginPost'])->name('admin-login-post');

    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin-dashboard');
        Route::get('/logout', [AdminController::class, 'adminLogout'])->name('admin-logout');
        Route::get('/profile', [AdminController::class, 'adminProfile'])->name('admin-profile');
        Route::post('/profile/update', [AdminController::class, 'adminProfileUpdate'])->name('admin-profile-update');
        Route::get('/change/password', [AdminController::class, 'changePassword'])->name('admin-change-password');
        Route::post('/change/password/update', [AdminController::class, 'updatePassword'])->name('admin-change-password-update');
        Route::get('/all', [AdminController::class, 'allAdmin'])->name('admin-all-list');
        Route::get('/create', [AdminController::class, 'addAdmin'])->name('admin-create');
        Route::post('/store', [AdminController::class, 'storeAdmin'])->name('admin-store');
        Route::get('/edit/{id}', [AdminController::class, 'editAdmin'])->name('admin-edit');
        Route::post('/update', [AdminController::class, 'updateAdmin'])->name('admin-update');
        Route::get('/delete/{id}', [AdminController::class, 'deleteAdmin'])->name('admin-delete');
        Route::get('/inactive/{id}', [AdminController::class, 'inactive'])->name('admin-inactive');
        Route::get('/active/{id}', [AdminController::class, 'active'])->name('admin-active');
        Route::get('/live-tv', [LiveTvController::class, 'index'])->name('live-tv');
        Route::post('/live-tv/update', [LiveTvController::class, 'update'])->name('live-tv-update');

        Route::get('/categories', [ContentController::class, 'categories'])->name('category');
        Route::get('/categories/create', [ContentController::class, 'createCategory'])->name('category-create');
        Route::post('/categories/store', [ContentController::class, 'storeCategory'])->name('category-store');
        Route::get('/categories/{category}/edit', [ContentController::class, 'editCategory'])->name('category-edit');
        Route::post('/categories/{category}/update', [ContentController::class, 'updateCategory'])->name('category-update');
        Route::get('/categories/{category}/delete', [ContentController::class, 'deleteCategory'])->name('category-delete');

        Route::get('/subcategories', [ContentController::class, 'subcategories'])->name('subcategory');
        Route::get('/subcategories/create', [ContentController::class, 'createSubcategory'])->name('subcategory-create');
        Route::post('/subcategories/store', [ContentController::class, 'storeSubcategory'])->name('subcategory-store');
        Route::get('/subcategories/{subcategory}/edit', [ContentController::class, 'editSubcategory'])->name('subcategory-edit');
        Route::post('/subcategories/{subcategory}/update', [ContentController::class, 'updateSubcategory'])->name('subcategory-update');
        Route::get('/subcategories/{subcategory}/delete', [ContentController::class, 'deleteSubcategory'])->name('subcategory-delete');

        Route::get('/news', [ContentController::class, 'news'])->name('news');
        Route::get('/news/create', [ContentController::class, 'createNews'])->name('news-create');
        Route::post('/news/store', [ContentController::class, 'storeNews'])->name('news-store');
        Route::get('/news/{news}/edit', [ContentController::class, 'editNews'])->name('news-edit');
        Route::post('/news/{news}/update', [ContentController::class, 'updateNews'])->name('news-update');
        Route::get('/news/{news}/delete', [ContentController::class, 'deleteNews'])->name('news-delete');
        Route::get('/news/{news}/toggle', [ContentController::class, 'toggleNews'])->name('news-toggle');

        Route::get('/banner', [ContentController::class, 'banner'])->name('banner');
        Route::post('/banner/update', [ContentController::class, 'updateBanner'])->name('banner-update');
        Route::get('/seo', [ContentController::class, 'seo'])->name('seo');
        Route::post('/seo/update', [ContentController::class, 'updateSeo'])->name('seo-update');

        Route::get('/photos', [ContentController::class, 'photos'])->name('photo');
        Route::get('/photos/create', [ContentController::class, 'createPhoto'])->name('photo-create');
        Route::post('/photos/store', [ContentController::class, 'storePhoto'])->name('photo-store');
        Route::get('/photos/{photo}/delete', [ContentController::class, 'deletePhoto'])->name('photo-delete');

        Route::get('/videos', [ContentController::class, 'videos'])->name('video');
        Route::get('/videos/create', [ContentController::class, 'createVideo'])->name('video-create');
        Route::post('/videos/store', [ContentController::class, 'storeVideo'])->name('video-store');
        Route::get('/videos/{video}/delete', [ContentController::class, 'deleteVideo'])->name('video-delete');

        Route::get('/reviews/pending', [ContentController::class, 'pendingReviews'])->name('pending-reviews');
        Route::get('/reviews/approved', [ContentController::class, 'approvedReviews'])->name('approved-reviews');
        Route::get('/reviews/{review}/approve', [ContentController::class, 'approveReview'])->name('review-approve');
        Route::get('/reviews/{review}/delete', [ContentController::class, 'deleteReview'])->name('review-delete');
        Route::get('/users', [ContentController::class, 'users'])->name('user-list');
        Route::get('/contacts', [ContentController::class, 'contacts'])->name('contact-list');
    });
});

Route::prefix('user')->group(function () {
    Route::get('/register', [UserController::class, 'register'])->name('user-register');
    Route::get('/login', [UserController::class, 'login'])->name('user-login');

    Route::post('/register/post', [UserController::class, 'registerPost'])->name('user-register-post');
    Route::post('/login/post', [UserController::class, 'loginPost'])->name('user-login-post');

    Route::middleware('auth:web')->group(function () {
        Route::get('/dashboard', [UserController::class, 'userDashboard'])->name('user-dashboard');
        Route::post('/profile/update', [UserController::class, 'userProfileUpdate'])->name('user-profile-update');
        Route::get('/change/password', [UserController::class, 'userChangePassword'])->name('user-change-password');
        Route::post('/change/password/update', [UserController::class, 'userChangePasswordUpdate'])->name('user-change-password-update');
        Route::get('/logout', [UserController::class, 'userLogout'])->name('user-logout');
        Route::post('/review/post', [HomeController::class, 'reviewPost'])->name('review-post');
    });
});
