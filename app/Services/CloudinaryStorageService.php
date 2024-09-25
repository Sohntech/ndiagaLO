<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use App\Interfaces\CloudStorageServiceInterface;

class CloudinaryStorageService implements CloudStorageServiceInterface
{
    protected $cloudinary;

    public function __construct(Cloudinary $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }

    public function uploadImage(string $filePath, string $path): string
    {
        $result = $this->cloudinary->uploadApi()->upload(
            $filePath,
            ['folder' => $path]
        );

        return $result['secure_url'];
    }
}
