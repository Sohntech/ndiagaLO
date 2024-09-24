<?php

namespace App\Interfaces;

interface CloudStorageServiceInterface
{
    public function uploadImage(string $filePath, string $path): string; 
}
