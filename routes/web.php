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
Route::get('/products/search', [OpenFoodFactsController::class, 'searchByName'])->name('products.searchByName');
Route::get('/api/products/search', [OpenFoodFactsController::class, 'apiSearchByName'])->name('api.products.search');
Route::get('/products/{id}', [OpenFoodFactsController::class, 'show'])->name('products.show');
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
    Route::get('/profile/badges', [ProfileController::class, 'badges'])->name('profile.badges');
    
    // Routes d'onboarding
    Route::get('/onboarding', [OnboardingController::class, 'index'])->name('onboarding.index');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');
    
    // Routes du feed social (style BeReal de la nourriture)
    Route::get('/social', [SocialFeedController::class, 'index'])->name('social.feed');
    Route::get('/social/create', [SocialFeedController::class, 'create'])->name('social.create');
    Route::post('/social', [SocialFeedController::class, 'store'])->name('social.store');
    Route::get('/social/{post}', [SocialFeedController::class, 'show'])->name('social.show');
    Route::get('/social/{post}/edit', [SocialFeedController::class, 'edit'])->name('social.edit');
    Route::patch('/social/{post}', [SocialFeedController::class, 'update'])->name('social.update');
    Route::delete('/social/{post}', [SocialFeedController::class, 'destroy'])->name('social.destroy');
    Route::post('/social/{post}/like', [SocialFeedController::class, 'like'])->name('social.like');
    Route::post('/social/{post}/comment', [SocialFeedController::class, 'comment'])->name('social.comment');
    // Les repas sont automatiquement analysés par l'IA lors de l'upload
    // Routes pour les ligues
    Route::get('/leagues', [App\Http\Controllers\LeagueController::class, 'index'])->name('leagues.index');
    Route::get('/leagues/create', [App\Http\Controllers\LeagueController::class, 'create'])->name('leagues.create');
    Route::post('/leagues', [App\Http\Controllers\LeagueController::class, 'store'])->name('leagues.store');
    Route::get('/leagues/meal-upload', [App\Http\Controllers\LeagueController::class, 'mealUpload'])->name('leagues.meal.upload');
    Route::post('/leagues/meal-store', [App\Http\Controllers\LeagueController::class, 'mealStore'])->name('leagues.meal.store');
    Route::get('/leagues/{slug}', [App\Http\Controllers\LeagueController::class, 'show'])->name('leagues.show');
    Route::post('/leagues/join', [App\Http\Controllers\LeagueController::class, 'join'])->name('leagues.join');
    Route::delete('/leagues/{slug}/leave', [App\Http\Controllers\LeagueController::class, 'leave'])->name('leagues.leave');
    Route::patch('/leagues/{slug}/members/{userId}', [App\Http\Controllers\LeagueController::class, 'updateMemberRole'])->name('leagues.updateMemberRole');
    Route::delete('/leagues/{slug}/members/{userId}', [App\Http\Controllers\LeagueController::class, 'removeMember'])->name('leagues.removeMember');
    Route::get('/leaderboard', [App\Http\Controllers\LeagueController::class, 'globalLeaderboard'])->name('leaderboard.global');
});

// Routes d'authentification (login, register, password reset, etc.)
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
