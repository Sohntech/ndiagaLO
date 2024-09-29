<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;

class FirebaseService
{
    protected $database;

    public function __construct()
    {
        // Récupérer les credentials encodés en base64 depuis le fichier .env
        $firebaseCredentialsBase64 = env('FIREBASE_CREDENTIALS_BASE64');

        // Décoder les credentials en base64
        $firebaseCredentialsJson = base64_decode($firebaseCredentialsBase64);

        // Sauvegarder temporairement le fichier décodé dans un fichier JSON
        $temporaryFilePath = sys_get_temp_dir() . '/firebase_credentials.json';
        file_put_contents($temporaryFilePath, $firebaseCredentialsJson);

        // Spécifiez le fichier temporaire comme chemin de clé de service
        $factory = (new Factory)
            ->withServiceAccount($temporaryFilePath)
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));

        // Initialisation de la base de données Firebase
        $this->database = $factory->createDatabase();

        // Supprimer le fichier temporaire après utilisation
        unlink($temporaryFilePath);
    }

    public function getDatabase(): Database
    {
        return $this->database;
    }

    // Les autres méthodes...
}