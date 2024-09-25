<?php

namespace App\Services;

use Kreait\Firebase\Storage;
use App\Interfaces\CloudStorageServiceInterface;

class FirebaseStorageService implements CloudStorageServiceInterface
{
    protected $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function uploadImage(string $filePath, $path): string
    {
        $bucket = $this->storage->getBucket();
        $fileName = "{$path}/{$filePath}";

        $bucket->upload(
            file_get_contents($filePath),
            [
                'name' => $fileName,
            ]
        );
        return $bucket->object($fileName)->signedUrl(new \DateTime('+1 year'));
    }
    
}
