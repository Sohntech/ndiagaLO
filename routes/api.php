<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\ApprenantsController;
use App\Http\Controllers\ReferentielController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/refresh', [AuthController::class, 'refresh']);

// Route::middleware(['auth:api', 'blacklisted'])->group(function () {
Route::post('/logout', [AuthController::class, 'logout']);

Route::apiResource('/users', UserController::class);
Route::get('/users/auth/{provider}', [AuthController::class, 'redirectToProvider']);
Route::get('/users/auth/{provider}/callback', [AuthController::class, 'handleProviderCallback']);

Route::apiResource('promotions', PromotionController::class);
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
Route::get('apprenants/change-password', [ApprenantsController::class, 'showChangePasswordForm'])->name('change-password');
Route::post('apprenants/change-password', [ApprenantsController::class, 'changePassword']);

Route::apiResource('referentiels', ReferentielController::class);
Route::get('archive/referentiels', [ReferentielController::class, 'archived']);
Route::get('referentiels/export', [ReferentielController::class, 'export']);
Route::post('/referentiels', [ReferentielController::class, 'store'])->name('referentiels.store');
Route::post('/referentiels/{referentielId}/competences', [ReferentielController::class, 'addCompetenceToReferentiel'])->name('referentiels.competences.add');
Route::put('/competences/{competenceId}', [ReferentielController::class, 'updateCompetence'])->name('competences.update');
Route::delete('/competences/{competenceId}', [ReferentielController::class, 'deleteCompetence'])->name('competences.delete');
Route::post('/competences/{competenceId}/modules', [ReferentielController::class, 'addModuleToCompetence'])->name('competences.modules.add');
Route::get('/competences/{competenceId}/modules', [ReferentielController::class, 'getModulesByCompetenceId'])->name('competences.modules.list');
Route::put('/modules/{moduleId}', [ReferentielController::class, 'updateModule'])->name('modules.update');
Route::delete('/modules/{moduleId}', [ReferentielController::class, 'deleteModule'])->name('modules.delete');

Route::post('notes/modules/{id}', [NoteController::class, 'addNotesToModule']);
Route::post('notes/apprenants', [NoteController::class, 'addNotesToApprenant']);
Route::patch('notes/apprenants/{id}', [NoteController::class, 'updateApprenantNotes']);
Route::get('notes/referentiels/{id}', [NoteController::class, 'getNotesForReferentiel']);
Route::get('notes/export/referentiels/{id}', [NoteController::class, 'exportNotesReferentiel']);
Route::get('notes/export/apprenants/{id}', [NoteController::class, 'exportNotesApprenant']);

// });
