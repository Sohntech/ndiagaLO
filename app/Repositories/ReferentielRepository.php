<?php

namespace App\Repositories;

use App\Enums\EtatReferentiel;
use App\Facades\ReferentielFacade;
use App\Interfaces\ReferentielRepositoryInterface;
use App\Services\FirebaseService;

class ReferentielRepository implements ReferentielRepositoryInterface
{
    protected $firebaseService;

    public function __construct(ReferentielFacade $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function all()
    {
        return ReferentielFacade::all();
    }


    public function find($id)
    {
        return ReferentielFacade::find($id);
    }

    public function create(array $data)
    {
        return ReferentielFacade::create($data);
    }

    public function update($id, array $data)
    {
        return ReferentielFacade::update($id)->set($data);
    }

    public function delete($id)
    {
        return (array) ReferentielFacade::delete($id);
    }

    public function findById($id)
    {
        return $this->find($id);
    }

    public function getAllReferentiels()
    {
        return $this->all();
    }

    public function getReferentielsActifs()
    {
        $referentiels = $this->all();
        return array_filter($referentiels, function ($referentiel) {
            return $referentiel['etat'] === EtatReferentiel::ACTIF->value;
        });
    }

    public function deleteReferentiel($id)
    {
        return $this->delete($id);
    }

    public function getArchivedReferentiels()
    {
        $referentiels = $this->all();
        return array_filter($referentiels, function ($referentiel) {
            return $referentiel['etat'] === EtatReferentiel::ARCHIVE->value;
        });
    }

    public function findByCode($code)
    {
        $referentiels = $this->all();
        foreach ($referentiels as $id => $referentiel) {
            if ($referentiel['code'] === $code) {
                return ['id' => $id] + $referentiel;
            }
        }
        return null;
    }

    public function addCompetenceToReferentiel($referentielId, array $competence)
    {
        $competencesRef = ReferentielFacade::getChild($referentielId)->getChild('competences');
        $competenceRef = $competencesRef->push($competence);

        error_log('Compétence ajoutée : ' . json_encode($competence) . ' avec ID : ' . $competenceRef->getKey());

        return ['id' => $competenceRef->getKey()] + $competence;
    }

    public function getCompetencesByReferentielId($referentielId)
    {
        $competences = ReferentielFacade::getChild($referentielId)->getChild('competences')->getValue() ?? [];
        error_log('Compétences récupérées pour le référentiel ' . $referentielId . ': ' . json_encode($competences));
        return $competences;
    }


    public function getReferentielById($id)
    {
        $referentiel = $this->find($id);
        if ($referentiel) {
            $referentiel->competences = $this->getCompetencesByReferentielId($id);
        }
        return $referentiel;
    }
}