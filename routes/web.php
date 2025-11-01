<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\Api\CharacterController; // Agregado para API Simpsons

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí van las rutas web de tu aplicación (Livewire, Fortify, etc.)
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

/*
|--------------------------------------------------------------------------
| API Routes en Web (opcional)
|--------------------------------------------------------------------------
|
| Si quieres exponer tu API de Simpsons directamente desde web.php,
| aunque lo recomendable es usar routes/api.php.
|
*/

// Listar todos los personajes
Route::get('api/characters', [CharacterController::class, 'index']);

// Importar personajes desde la API externa
Route::get('api/characters/import', [CharacterController::class, 'import']);

// Mostrar un personaje específico
Route::get('api/characters/{character}', [CharacterController::class, 'show']);
