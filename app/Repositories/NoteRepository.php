<?php

// app/Repositories/NoteRepository.php
namespace App\Repositories;

use App\Models\Note;
use Illuminate\Support\Facades\DB;
use App\Interfaces\NoteRepositoryInterface;

class NoteRepository implements NoteRepositoryInterface
{
    protected $note;

    public function __construct(Note $note)
    {
        $this->note = $note;
    }


    public function addNotesToModule(int $moduleId, array $notes): array
    {
        $createdNotes = [];
        foreach ($notes as $noteData) {
            $createdNotes[] = $this->note->create([
                'apprenant_id' => $noteData['apprenantId'],
                'module_id' => $moduleId,
                'note' => $noteData['note']
            ]);
        }
        return $createdNotes;
    }

    public function addNotesToApprenant(int $apprenantId, array $notes): array
    {
        $createdNotes = [];
        foreach ($notes as $noteData) {
            $createdNotes[] = $this->note->create([
                'apprenant_id' => $apprenantId,
                'module_id' => $noteData['moduleId'],
                'note' => $noteData['note']
            ]);
        }
        return $createdNotes;
    }

    public function updateApprenantNotes(int $apprenantId, array $notes): array
    {
        $updatedNotes = [];
        foreach ($notes as $noteData) {
            $note = $this->note->findOrFail($noteData['noteId']);
            $note->update(['note' => $noteData['note']]);
            $updatedNotes[] = $note;
        }
        return $updatedNotes;
    }

    public function getNotesForReferentiel(int $referentielId): array
    {
        $allNotes = $this->note->all(); // Récupère toutes les notes
        return array_filter($allNotes, function ($note) use ($referentielId) {
            return isset($note['apprenant']['referentiel_id']) && $note['apprenant']['referentiel_id'] == $referentielId;
        });
    }

    public function getNotesForApprenant(int $apprenantId): array
    {
        $allNotes = $this->note->all(); // Récupère toutes les notes
        return array_filter($allNotes, function ($note) use ($apprenantId) {
            return isset($note['apprenant_id']) && $note['apprenant_id'] == $apprenantId;
        });
    }
}
