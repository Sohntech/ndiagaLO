<?php

namespace App\Repositories;

use App\Interfaces\EmargementRepositoryInterface;
use Carbon\Carbon;
use App\Services\FirebaseService;

class EmargementRepository implements EmargementRepositoryInterface
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function createForGroup(array $apprenantIds, $date)
    {
        $emargements = [];
        foreach ($apprenantIds as $apprenantId) {
            $emargement = [
                    'apprenant_id' => $apprenantId,
                    'date' => $date,
                    'statut' => 'absent',
                    'heure_entree' => null,
                    'heure_sortie' => null,
            ];
            $this->firebase->getDatabase()->getReference("emargements/$apprenantId/$date")->set($emargement);
            $emargements[] = $emargement;
        }
        return $emargements;
    }

    public function createOrUpdateForApprenant($apprenantId, $date)
    {
        $reference = $this->firebase->getDatabase()->getReference("emargements/$apprenantId/$date");
        $emargement = $reference->getValue();
        $now = Carbon::now();

        if (!$emargement || !isset($emargement['heure_entree'])) {
            $emargement = [
                'apprenant_id' => $apprenantId,
                'date' => $date,
                'heure_entree' => $now->toDateTimeString(),
                'statut' => $now->hour >= 8 ? 'retard' : 'present',
            ];
        } else {
            $emargement['heure_sortie'] = $now->toDateTimeString();
        }

        $reference->set($emargement);
        return $emargement;
    }

    public function getForPromotion($promotionId, array $filters = [])
    {
        $query = $this->firebase->getDatabase()->getReference("promotions/$promotionId/emargements")->getValue();

        // Filtrage des données selon les paramètres reçus
        $filteredEmargements = [];
        foreach ($query as $emargement) {
            if (isset($filters['mois']) && Carbon::parse($emargement['date'])->month != $filters['mois']) {
                continue;
            }
            if (isset($filters['date']) && $emargement['date'] != $filters['date']) {
                continue;
            }
            $filteredEmargements[] = $emargement;
        }

        return $filteredEmargements;
    }

    public function updateForApprenant($apprenantId, $date, array $data)
    {
        $reference = $this->firebase->getDatabase()->getReference("emargements/$apprenantId/$date");
        $emargement = $reference->getValue();

        if ($emargement) {
            $reference->update($data);
        }

        return $reference->getValue();
    }

    public function markAbsentees(Carbon $date)
    {
        $dateString = $date->toDateString();
        $allEmargements = $this->firebase->getDatabase()->getReference("emargements")->getValue();
        $absentsCount = 0;

        foreach ($allEmargements as $apprenantId => $emargements) {
            if (isset($emargements[$dateString]) && !isset($emargements[$dateString]['heure_entree'])) {
                $this->firebase->getDatabase()->getReference("emargements/$apprenantId/$dateString")
                    ->update(['statut' => 'absent']);
                $absentsCount++;
            }
        }

        return $absentsCount;
    }
}
