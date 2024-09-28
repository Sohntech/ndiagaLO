<?php

namespace App\Console\Commands;

use App\Interfaces\ApprenantsRepositoryInterface;
use Illuminate\Console\Command;
use App\Imports\ApprenantsImport;
use App\Imports\ApprenantsPdfImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportApprenants extends Command
{
    protected $signature = 'apprenants:import {file}';
    protected $description = 'Importer des apprenants depuis un fichier Excel ou PDF';

    public function handle(ApprenantsRepositoryInterface $repository)
    {
        $file = $this->argument('file');
        $extension = pathinfo($file, PATHINFO_EXTENSION);

        if ($extension === 'pdf') {
            $import = new ApprenantsPdfImport($repository);
            $import->import($file);
        } elseif (in_array($extension, ['xlsx', 'xls', 'csv'])) {
            $import = new ApprenantsImport($repository);
            Excel::import($import, $file);
        } else {
            $this->error('Format de fichier non supporté. Utilisez Excel (.xlsx, .xls, .csv) ou PDF.');
            return;
        }

        $this->info('Importation terminée.');
        if (!empty($import->failures())) {
            $this->warn('Certaines lignes n\'ont pas pu être importées. Vérifiez le fichier d\'erreurs.');
        }
    }
}