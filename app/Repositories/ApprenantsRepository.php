<?php

namespace App\Repositories;

use App\Facades\ApprenantsFacade;
use App\Services\LocalStorageService;
use App\Interfaces\ApprenantsRepositoryInterface;

class ApprenantsRepository implements ApprenantsRepositoryInterface
{
    protected $LocalStorageService;
    public function __construct(LocalStorageService $LocalStorageService)
    {
        $this->LocalStorageService = $LocalStorageService;
    }
    
    public function create(array $data)
    {
        $originalFileName = $data['photo']->getClientOriginalName();
        $localPath = $this->LocalStorageService->storeImageLocally('images/apprenants', $originalFileName);
        $data['photo_couverture'] = $localPath;
        $data['matricule'] = ApprenantsFacade::genererMatricule();
        $data['code_qr'] = ApprenantsFacade::genererCodeQR();
        $apprenant = ApprenantsFacade::create($data);
        return $apprenant;
    }

    public function update($id, array $data)
    {
        return ApprenantsFacade::update($id, $data);
    }

    public function find($id)
    {
        return ApprenantsFacade::find($id);
    }

    public function all(array $filters = []): mixed
    {
        $query = ApprenantsFacade::query();

        if (isset($filters['referentiel'])) {
            $query->where('referentiel', '=', $filters['referentiel']);
        }

        if (isset($filters['status'])) {
            $query->where('status', '=', $filters['status']);
        }

        return $query->get();
    }

    public function getTrashed()
    {
        return ApprenantsFacade::query()->where('deleted_at', '!=', null)->get();
    }

    public function restore($id)
    {
        $apprenant = (array) $this->find($id);
        if ($apprenant && isset($apprenant['deleted_at'])) {
            return $this->delete($apprenant['deleted_at']);
        }
        return false;
    }

    public function getInactive()
    {
        return ApprenantsFacade::query()->where('status', '=', 'Inactive')->get();
    }

    public function delete(array $id)
    {
        return ApprenantsFacade::delete($id);
    }

    public function forceDelete($id)
    {
        return ApprenantsFacade::delete($id);
    }

}
