<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Jobs\UploadProfilePictureJob;

class HandleProfilePictureUpload
{
    public function handle(UserCreated $event)
    {
        // Assure-toi de passer le chemin de la photo en 3ème argument
        $photoPath = $event->userMysql->photo; // ou une autre source pour le chemin de la photo
        
        // Dispatch du job avec les trois arguments nécessaires
        UploadProfilePictureJob::dispatch($event->userMysql->id, $event->userFirebase, $photoPath);
    }
}
