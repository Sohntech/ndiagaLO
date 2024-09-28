<?php

namespace App\Imports;

use App\Notifications\WelcomeApprenant;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Facades\Notification;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use App\Interfaces\ApprenantsRepositoryInterface;

class ApprenantsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    protected $repository;
    protected $failures = [];

    public function __construct(ApprenantsRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function model(array $row)
    {
        $apprenant = $this->repository->create([
            'nom' => $row['nom'],
            'prenom' => $row['prenom'],
            'email' => $row['email'],
            'date_naissance' => $row['date_naissance'],
            'sexe' => $row['sexe'],
            'referentiel' => $row['referentiel'],
            'password' => 'Passer@123',
        ]);
        Notification::send($apprenant, new WelcomeApprenant($apprenant));
        return $apprenant;
    }

    public function rules(): array
    {
        return [
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required|email|unique:apprenants,email',
            'date_naissance' => 'required|date',
            'sexe' => 'required|in:M,F',
            'referentiel' => 'required',
        ];
    }

    public function onFailure(Failure ...$failures)
    {
        $this->failures = array_merge($this->failures, $failures);
    }

    public function failures()
    {
        return collect($this->failures);
    }
}