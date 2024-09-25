<?php

namespace App\Console\Commands;

use App\Interfaces\ApprenantsRepositoryInterface;
use Illuminate\Console\Command;
use App\Imports\ApprenantsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Repositories\ApprenantRepository;

class ImportApprenants extends Command
{
    protected $signature = 'apprenants:import {file}';
    protected $description = 'Importer des apprenants depuis un fichier Excel';

    public function handle(ApprenantsRepositoryInterface $repository)
    {
        $file = $this->argument('file');
        $import = new ApprenantsImport($repository);
        Excel::import($import, $file);

        $this->info('Importation terminée.');
        if ($import->failures()->isNotEmpty()) {
            $this->warn('Certaines lignes n\'ont pas pu être importées. Vérifiez le fichier d\'erreurs.');
        }
    }
}