<?php

namespace App\Imports;

use Smalot\PdfParser\Parser;
use Maatwebsite\Excel\Validators\Failure;
use App\Interfaces\ApprenantsRepositoryInterface;

class ApprenantsPdfImport
{
    protected $repository;
    protected $failures = [];

    public function __construct(ApprenantsRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function import($file)
    {
        $parser = new Parser();
        $pdf = $parser->parseFile($file);
        $text = $pdf->getText();

        // Logique pour extraire les données du texte PDF
        // Ceci est un exemple simple, vous devrez l'adapter à votre format PDF spécifique
        $lines = explode("\n", $text);
        foreach ($lines as $line) {
            $data = $this->parseLine($line);
            if ($data) {
                try {
                    $this->repository->create($data);
                } catch (\Exception $e) {
                    $this->failures[] = new Failure(0, 'all', [$e->getMessage()], $data);
                }
            }
        }
    }

    protected function parseLine($line)
    {
        // Exemple de parsing simple, à adapter selon votre format PDF
        $parts = explode(',', $line);
        if (count($parts) >= 5) {
            return [
                'nom' => trim($parts[0]),
                'prenom' => trim($parts[1]),
                'email' => trim($parts[2]),
                'date_naissance' => trim($parts[3]),
                'referentiel' => trim($parts[4]),
                // Ajoutez d'autres champs selon vos besoins
            ];
        }
        return null;
    }

    public function failures()
    {
        return collect($this->failures);
    }
}
