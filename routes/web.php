<?php

use App\Http\Controllers\Admin\UserAdminController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// Page d'accueil
Route::get('/', [App\Http\Controllers\main::class, 'index'])->name('home');

// Page principale
Route::get('/home', [App\Http\Controllers\main::class, 'index'])->name('home');

Route::get('/apropos', [App\Http\Controllers\apropos::class, 'index'])->name('apropos');

//page de traitement des connexions
Route::get('/login', [App\Http\Controllers\UserConnectForm::class, 'index'])->name('login');
Route::post('/login', [App\Http\Controllers\UserConnectForm::class, 'connect'])->name('login');

// Page permettre à l'utilisateur de nous contacter
Route::get('/contact', [App\Http\Controllers\ContactController::class, 'index'])->name('contact');
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'contact'])->name('contact');

// Page de création de user
Route::get('/register', [App\Http\Controllers\UserController::class, 'index'])->name('register');
Route::post('/register', [App\Http\Controllers\UserController::class, 'create'])->name('register');

//Page de modification des informations utilisateur
Route::get('/parametre', [App\Http\Controllers\UserController::class, 'parametre'])->name('parametre');
Route::post('/parametre', [App\Http\Controllers\UserController::class, 'update'])->name('parametre');
Route::delete('/parametre', [App\Http\Controllers\UserController::class, 'destroy'])->name('parametre');

// Page de modification de mot de passe
Route::get('/new-password', [App\Http\Controllers\UserController::class, 'edit_password'])->name('new-password');
Route::post('/new-password', [App\Http\Controllers\UserController::class, 'updatePassword'])->name('new-password');

// Page de réinitialisation de mot de passe
Route::get('/forgot-password', [App\Http\Controllers\ForgotPassword::class, 'showForgotPasswordForm'])->name('forgot-password');
Route::post('/forgot-password', [App\Http\Controllers\ForgotPassword::class, 'sendPasswordResetLink'])->middleware('guest')->name('password.email');
Route::get('/reset_password/{token}', [App\Http\Controllers\ForgotPassword::class, 'showResetPasswordForm'])->middleware('guest')->name('reset_password');
Route::post('/reset_password', [App\Http\Controllers\ForgotPassword::class, 'resetPassword'])->middleware('guest')->name('password-update');

//Système de déconnexion
Route::get('/logout', [App\Http\Controllers\logout::class, 'index'])->name('logout');

//page de traitement des images
Route::get('/image-processing', [App\Http\Controllers\ImageController::class, 'index'])->name('image_processing');
Route::post('/image-processing', [App\Http\Controllers\ImageController::class, 'upload'])->name('image_processing');
Route::get('/images/{id}', [App\Http\Controllers\ImageController::class, 'show'])->name('image.show');
Route::delete('/images/{id}', [App\Http\Controllers\ImageController::class, 'delete'])->name('image.delete');

//page de traitement des videos
Route::get('/video-processing', [App\Http\Controllers\VideoController::class, 'index'])->name('video_processing');
Route::post('/video-processing', [App\Http\Controllers\VideoController::class, 'upload'])->name('video_processing');
Route::get('/videos/{id}', [App\Http\Controllers\VideoController::class, 'streamVideo'])->name('video.stream');
Route::delete('/videos/{id}', [App\Http\Controllers\VideoController::class, 'delete'])->name('video.delete');

//page de traitement des webcams
Route::get('/webcam-processing', [App\Http\Controllers\WebcamController::class, 'index'])->name('webcam_processing');

//Download
Route::get('/download/image/{id}', [App\Http\Controllers\DownloadController::class, 'downloadImage'])->name('download.image');
Route::get('/download/jsonImage/{id}', [App\Http\Controllers\DownloadController::class, 'downloadJsonImage'])->name('download.jsonImage');
Route::get('/download/video/{id}', [App\Http\Controllers\DownloadController::class, 'downloadVideo'])->name('download.video');
Route::get('/download/jsonVideo/{id}', [App\Http\Controllers\DownloadController::class, 'downloadJsonVideo'])->name('download.jsonVideo');
Route::get('/download/library/{id}', [App\Http\Controllers\DownloadController::class, 'downloadLibrary'])->name('download.library');

// page de gestion des librairies
Route::get('/library', [App\Http\Controllers\LibraryController::class, 'index'])->name('library.index');
Route::get('/library/{id}', [App\Http\Controllers\LibraryController::class, 'show'])->name('library.show');
Route::delete('/library/{id}', [App\Http\Controllers\LibraryController::class, 'delete'])->name('library.delete');

//page d'aide
Route::get('/help', function () {
    return view('help');
})->name('help');


//page RGPD
Route::get('/politique-de-confidentialite', function () {
    return view('RGPD.politique-de-confidentialite');
});

Route::get("/condition-d'utilisation", function () {
    return view("RGPD.condition-d'utilisation");
});

Route::get('/mention-legales', function () {
    return view('RGPD.mention-legales');
});

// page admin
Route::prefix('admin')->name('admin.')->group( function (){
    Route::resource('UserAdmin', UserAdminController::class)->except(['show']);
    // Route suppression de libraries de l'utilisateur
    Route::delete('UserAdmin/{id}/destroyLibrairy', [App\Http\Controllers\Admin\UserAdminController::class, 'destroyLibrairy'])->name('UserAdmin.destroyLibrairy');
});

// Verification de l'adresse mail
Route::get(
    uri: '/email/verify/{id}/{hash}',
    action : [\App\Http\Controllers\UserController::class, 'verifyEmail'])
    ->middleware(['signed'])
    ->name('verification.verify');

// Changement de langage
Route::get('change-language/{locale}', function ($locale) {
    if (array_key_exists($locale, config('app.locales'))) {
        Session::put('applocale', $locale);
    }
    return back();
})->name('change_language');

