<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Interfaces\ApprenantsServiceInterface;

class ApprenantsController extends Controller
{
    protected $service;

    public function __construct(ApprenantsServiceInterface $service)
    {
        $this->service = $service;
        // $this->authorizeResource(ApprenantFirebase::class, 'apprenant');
    }

    public function store(Request $request)
    {
        return $this->service->registerApprenant($request->all());
    }

    public function update(Request $request, $id)
    {
        return $this->service->updateApprenantDetails($id, $request->all());
    }

    public function show($id)
    {
        return $this->service->getApprenantDetails($id);
    }

    public function index(Request $request)
    {
        $filters = $request->only(['referentiel', 'status']);
        return $this->service->listAllApprenants($filters);
    }

    public function destroy($id)
    {
        return $this->service->removeApprenant($id);
    }

    public function trashed()
    {
        return $this->service->listDeletedApprenants();
    }

    public function restore($id)
    {
       return $this->service->restoreApprenant($id);
    }

    public function forceDelete($id)
    {
       return $this->service->permanentlyDeleteApprenant($id);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        $result = $this->service->importApprenants($request->file('file'));

        if ($result['success']) {
            return response()->json(['message' => 'Import successful'], 200);
        } else {
            return response()->json([
                'message' => 'Some rows failed to import',
                'failed_file' => $result['failed_file']
            ], 422);
        }
    }

    public function inactive()
    {
        return $this->service->getInactiveApprenants();
    }

    public function sendRelance(Request $request)
    {
        $apprenantIds = $request->input('apprenant_ids');
        return $this->service->sendRelanceToInactiveApprenants($apprenantIds);
    }

    public function sendRelanceToOne($id)
    {
        return $this->service->sendRelanceToInactiveApprenants([$id]);
    }

    public function changePassword(Request $request)
{
    $request->validate([
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = Auth::user();
    $user->password = Hash::make($request->password);
    $user->password_changed = true;

    return $user;
}

}