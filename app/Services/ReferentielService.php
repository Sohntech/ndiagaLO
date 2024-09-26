<?php

namespace App\Services;

use App\Enums\EtatReferentiel;
use App\Interfaces\ReferentielServiceInterface;
use App\Interfaces\ReferentielRepositoryInterface;

class ReferentielService implements ReferentielServiceInterface
{
    protected $referentielRepository;

    public function __construct(ReferentielRepositoryInterface $referentielRepository)
    {
        $this->referentielRepository = $referentielRepository;
    }

    public function createReferentiel(array $data)
    {
        $data['etat'] = EtatReferentiel::ACTIF->value;
        if (isset($data['competences']) && is_array($data['competences'])) {
            $competences = array_map(function ($competence) {
                return [
                    'titre' => trim($competence['titre']),
                    'description' => trim($competence['description']),
                ];
            }, $data['competences']);
        } else {
            $competences = [];
        }
        $referentiel = $this->referentielRepository->create($data);
        foreach ($competences as $competence) {
            $this->referentielRepository->addCompetenceToReferentiel($referentiel['id'], $competence);
        }
        $referentiel['competences'] = $this->referentielRepository->getCompetencesByReferentielId($referentiel['id']);
        return $referentiel;
    }


    public function updateReferentiel($id, array $data)
    {
        $referentiel = $this->referentielRepository->getReferentielById($id);
        if (isset($data['code']) && $data['code'] !== $referentiel['code']) {
            if ($this->referentielRepository->findByCode($data['code'])) {
                throw new \Exception("Le code du référentiel doit être unique.");
            }
        }
        return $this->referentielRepository->update($id, $data);
    }

    public function getReferentielById($id)
    {
        return $this->referentielRepository->getReferentielById($id);
    }

    public function getAllReferentiels()
    {
        return $this->referentielRepository->getAllReferentiels();
    }

    public function deleteReferentiel($id)
    {
        $referentiel = $this->referentielRepository->getReferentielById($id);
        return $this->referentielRepository->update($id, ['etat' => EtatReferentiel::ARCHIVE->value]);
    }

    public function getCompetencesByReferentielId($referentielId)
    {
        $competencesRef = $this->referentielRepository->getCompetencesByReferentielId("referentiels/{$referentielId}/competences");
        return $competencesRef->getValue();
    }

    public function getReferentielsActifs()
    {
        $referentiels = $this->getAllReferentiels();
        return array_filter($referentiels, function ($referentiel) {
            return $referentiel['etat'] === 'actif';
        });
    }

    public function getArchivedReferentiels()
    {
        $referentiels = $this->getAllReferentiels();
        return array_filter($referentiels, function ($referentiel) {
            return $referentiel['etat'] === 'archivé';
        });
    }
}