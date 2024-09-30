<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\ApprenantsController;
use App\Http\Controllers\EmargementController;
use App\Http\Controllers\ReferentielController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/refresh', [AuthController::class, 'refresh']);
Route::prefix('auth')->group(function () {
    Route::get('/{provider}', [AuthController::class, 'redirectToProvider']);
    Route::get('/{provider}/callback', [AuthController::class, 'handleProviderCallback']);
});

// Route::middleware(['auth:api', 'blacklisted'])->group(function () {

Route::post('/logout', [AuthController::class, 'logout']);

Route::apiResource('users', UserController::class);

Route::prefix('promotions')->group(function () {
    Route::apiResource('', PromotionController::class);
    Route::get('/encours', [PromotionController::class, 'getPromotionEncours']);
    Route::get('/{id}/stats', [PromotionController::class, 'getStats']);
    Route::patch('/{id}/etat', [PromotionController::class, 'changeStatus']);
    Route::patch('/{id}/cloturer', [PromotionController::class, 'cloturer']);
    Route::patch('/{id}', [PromotionController::class, 'update']);
    Route::get('/export/{format}', [PromotionController::class, 'export']);
});

Route::prefix('apprenants')->group(function () {
    Route::apiResource('', ApprenantsController::class);
    Route::post('/import', [ApprenantsController::class, 'import']);
    Route::get('/trashed', [ApprenantsController::class, 'trashed']);
    Route::post('/{id}/restore', [ApprenantsController::class, 'restore']);
    Route::delete('/{id}/force', [ApprenantsController::class, 'forceDelete']);
    Route::get('/inactive', [ApprenantsController::class, 'inactive']);
    Route::post('/relance', [ApprenantsController::class, 'sendRelance']);
    Route::post('/{id}/relance', [ApprenantsController::class, 'sendRelanceToOne']);
    Route::get('/change-password', [ApprenantsController::class, 'showChangePasswordForm'])->name('change-password');
    Route::post('/change-password', [ApprenantsController::class, 'changePassword']);
});

Route::prefix('referentiels')->group(function () {
    Route::apiResource('', ReferentielController::class);
    Route::get('archive/', [ReferentielController::class, 'archived']);
    Route::get('/export', [ReferentielController::class, 'export']);
    Route::post('/', [ReferentielController::class, 'store'])->name('referentiels.store');
    Route::post('/{referentielId}/competences', [ReferentielController::class, 'addCompetenceToReferentiel'])->name('.competences.add');
    Route::put('/competences/{competenceId}', [ReferentielController::class, 'updateCompetence'])->name('competences.update');
    Route::delete('/competences/{competenceId}', [ReferentielController::class, 'deleteCompetence'])->name('competences.delete');
    Route::post('/competences/{competenceId}/modules', [ReferentielController::class, 'addModuleToCompetence'])->name('competences.modules.add');
    Route::get('/competences/{competenceId}/modules', [ReferentielController::class, 'getModulesByCompetenceId'])->name('competences.modules.list');
    Route::put('/modules/{moduleId}', [ReferentielController::class, 'updateModule'])->name('modules.update');
    Route::delete('/modules/{moduleId}', [ReferentielController::class, 'deleteModule'])->name('modules.delete');
});

Route::prefix('notes')->group(function () {
    Route::post('/modules/{id}', [NoteController::class, 'addNotesToModule']);
    Route::post('/apprenants', [NoteController::class, 'addNotesToApprenant']);
    Route::patch('/apprenants/{id}', [NoteController::class, 'updateApprenantNotes']);
    Route::get('/{id}', [NoteController::class, 'getNotesForReferentiel']);
    Route::get('/export//{id}', [NoteController::class, 'exportNotesReferentiel']);
    Route::get('/export/apprenants/{id}', [NoteController::class, 'exportNotesApprenant']);
});

Route::prefix('emargements')->group(function () {
    Route::post('', [EmargementController::class, 'enregistrerGroupe']);
    Route::get('', [EmargementController::class, 'lister']);
    Route::post('/apprenants/{id}', [EmargementController::class, 'enregistrerApprenant']);
    Route::patch('/apprenants/{id}', [EmargementController::class, 'modifier']);
    Route::post('/declencher-absences', [EmargementController::class, 'declencherAbsences']);
});

// });
