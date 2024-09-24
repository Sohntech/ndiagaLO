<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class LocalStorageService
{
    public function storeImageLocally($folder = 'images', $filename)
    {
        $filePath = $folder . '/' . $filename;
        Storage::disk('local')->put($filePath, $filePath);
        return storage_path('app/' . $filePath);
    }
}