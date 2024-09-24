<?php

namespace App\Console\Commands;

use App\Interfaces\ApprenantsRepositoryInterface;
use Illuminate\Console\Command;
use App\Exports\ApprenantsExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportApprenants extends Command
{
    protected $signature = 'apprenants:export {format=xlsx}';
    protected $description = 'Exporter les apprenants en format Excel ou PDF';

    public function handle(ApprenantsRepositoryInterface $repository)
    {
        $format = $this->argument('format');
        $fileName = 'apprenants_' . now()->format('Y-m-d_H-i-s') . '.' . $format;

        $export = new ApprenantsExport($repository);

        if ($format === 'pdf') {
            Excel::store($export, $fileName, 'public', \Maatwebsite\Excel\Excel::DOMPDF);
        } else {
            Excel::store($export, $fileName, 'public');
        }

        $this->info("Exportation terminée. Fichier : {$fileName}");
    }
}