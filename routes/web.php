<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public marketing site
|--------------------------------------------------------------------------
*/
Route::view('/', 'home')->name('home');
Route::view('/services', 'services')->name('services');
Route::view('/process', 'process')->name('process');
Route::view('/about', 'about')->name('about');
Route::view('/pricing', 'pricing')->name('pricing');
Route::view('/faq', 'faq')->name('faq');
Route::view('/funding', 'funding')->name('funding');

// Service detail pages
Route::view('/credit-repair', 'services.credit-repair')->name('credit-repair');
Route::view('/business-credit', 'services.business-credit')->name('business-credit');
Route::view('/debt-validation', 'services.debt-validation')->name('debt-validation');
Route::view('/credit-building', 'services.credit-building')->name('credit-building');
Route::view('/financial-coaching', 'services.financial-coaching')->name('financial-coaching');

Route::post('/lead', [LeadController::class, 'store'])->middleware('throttle:10,1')->name('lead.store');

// Funding qualifier funnel (Option C)
Route::get('/get-funded', [\App\Http\Controllers\QualifierController::class, 'show'])->name('qualify.show');
Route::post('/get-funded', [\App\Http\Controllers\QualifierController::class, 'submit'])->middleware('throttle:10,1')->name('qualify.submit');
Route::get('/get-funded/result/{outcome}', [\App\Http\Controllers\QualifierController::class, 'result'])->name('qualify.result');

// Legal pages
Route::view('/terms', 'legal.terms')->name('terms');
Route::view('/privacy', 'legal.privacy')->name('privacy');
Route::view('/disclaimer', 'legal.disclaimer')->name('disclaimer');

/*
|--------------------------------------------------------------------------
| Secure checkout (Authorize.Net)
|--------------------------------------------------------------------------
*/
Route::get('/checkout/{plan}', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout/{plan}', [CheckoutController::class, 'process'])->middleware('throttle:8,1')->name('checkout.process');
Route::get('/checkout/{payment}/thank-you', [CheckoutController::class, 'thankYou'])->name('checkout.thankyou');

/*
|--------------------------------------------------------------------------
| Credit-repair service agreement (post-payment, must sign before onboarding)
|--------------------------------------------------------------------------
*/
Route::get('/agreement/{token}', [\App\Http\Controllers\AgreementController::class, 'show'])->name('agreement.show');
Route::post('/agreement/{token}', [\App\Http\Controllers\AgreementController::class, 'sign'])->name('agreement.sign');

/*
|--------------------------------------------------------------------------
| Onboarding (post-payment, token-gated)
|--------------------------------------------------------------------------
*/
Route::get('/onboarding/{token}', [OnboardingController::class, 'show'])->name('onboarding.show');
Route::post('/onboarding/{token}', [OnboardingController::class, 'store'])->name('onboarding.store');
Route::get('/onboarding/{token}/next-steps', [OnboardingController::class, 'nextSteps'])->name('onboarding.next');

/*
|--------------------------------------------------------------------------
| Authorize.Net webhook (no CSRF — see VerifyCsrfToken except)
|--------------------------------------------------------------------------
*/
Route::post('/webhooks/authorizenet', [WebhookController::class, 'authorizenet'])->middleware('throttle:60,1')->name('webhooks.authorizenet');

/*
|--------------------------------------------------------------------------
| Admin dashboard
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'login'])->middleware('throttle:5,1')->name('admin.login.post');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::middleware('admin.auth')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('search', [DashboardController::class, 'search'])->name('admin.search');

        Route::get('leads', [DashboardController::class, 'leads'])->name('admin.leads');
        Route::get('lead/{lead}', [DashboardController::class, 'leadShow'])->name('admin.leads.show');
        Route::post('lead/{lead}/status', [DashboardController::class, 'leadUpdateStatus'])->name('admin.leads.status');
        Route::get('leads/{type}', [DashboardController::class, 'leads'])->name('admin.leads.type');

        Route::get('payments', [DashboardController::class, 'payments'])->name('admin.payments');
        Route::get('clients', [DashboardController::class, 'clients'])->name('admin.clients');
        Route::get('clients/{client}', [DashboardController::class, 'clientShow'])->name('admin.clients.show');

        Route::get('subscriptions', [DashboardController::class, 'subscriptions'])->name('admin.subscriptions');
        Route::get('subscriptions-at-risk', [DashboardController::class, 'subscriptionsAtRisk'])->name('admin.subscriptions.atrisk');
    });
});
