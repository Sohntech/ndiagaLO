<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\ApprenantsController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/refresh', [AuthController::class, 'refresh']);

Route::apiResource('/users', UserController::class);
// Route::middleware(['auth:api', 'blacklisted'])->group(function () {
Route::post('/logout', [AuthController::class, 'logout']);

Route::get('/auth/{provider}', [AuthController::class, 'redirectToProvider']);
Route::get('/auth/{provider}/callback', [AuthController::class, 'handleProviderCallback']);


Route::apiResource('promotions', PromotionController::class);
//Route::apiResource('referentiels', ReferentielController::class);

Route::get('promotions/encours', [PromotionController::class, 'getPromotionEncours']);
Route::get('promotions/{id}/stats', [PromotionController::class, 'getStats']);
Route::patch('promotions/{id}/etat', [PromotionController::class, 'changeStatus']);
Route::patch('promotions/{id}/cloturer', [PromotionController::class, 'cloturer']);
Route::patch('promotions/{id}/referentiels', [PromotionController::class, 'updateReferentiels']);
Route::get('promotions/export/{format}', [PromotionController::class, 'export']);


Route::apiResource('apprenants', ApprenantsController::class);
Route::post('apprenants/import', [ApprenantsController::class, 'import']);
Route::get('apprenants/trashed', [ApprenantsController::class, 'trashed']);
Route::post('apprenants/{id}/restore', [ApprenantsController::class, 'restore']);
Route::delete('apprenants/{id}/force', [ApprenantsController::class, 'forceDelete']);
Route::get('apprenants/inactive', [ApprenantsController::class, 'inactive']);
Route::post('apprenants/relance', [ApprenantsController::class, 'sendRelance']);
Route::post('apprenants/{id}/relance', [ApprenantsController::class, 'sendRelanceToOne']);

// });
