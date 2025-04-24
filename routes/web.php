<?php


use App\Http\Controllers\OpenFoodFactsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialFeedController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\StatisticsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', [OpenFoodFactsController::class, 'index'])->name('products.search');
Route::post('/fetch', [OpenFoodFactsController::class, 'fetch'])->name('products.fetch');
// Route::get('/statistics', [OpenFoodFactsController::class, 'statistics'])->name('statistics');

// Nouvelle route de statistiques
Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');
Route::get('/statistics/city/{city}', [StatisticsController::class, 'cityDetail'])->name('statistics.city');
Route::get('/api/statistics', [StatisticsController::class, 'apiStats'])->name('api.statistics');

// Profil utilisateur et Feed Social
Route::middleware(['auth'])->group(function () {
    // Routes du profil
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/posts', [ProfileController::class, 'posts'])->name('profile.posts');
    Route::get('/profile/points', [ProfileController::class, 'points'])->name('profile.points');
    
    // Routes d'onboarding
    Route::get('/onboarding', [OnboardingController::class, 'index'])->name('onboarding.index');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');
    
    // Routes du feed social (style BeReal de la nourriture)
    Route::get('/social', [SocialFeedController::class, 'index'])->name('social.feed');
    Route::get('/social/create', [SocialFeedController::class, 'create'])->name('social.create');
    Route::post('/social', [SocialFeedController::class, 'store'])->name('social.store');
    Route::get('/social/{post}', [SocialFeedController::class, 'show'])->name('social.show');
    Route::post('/social/{post}/like', [SocialFeedController::class, 'like'])->name('social.like');
    Route::post('/social/{post}/comment', [SocialFeedController::class, 'comment'])->name('social.comment');
});

// Routes d'authentification (login, register, password reset, etc.)
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
