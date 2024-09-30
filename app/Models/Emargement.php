<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emargement extends Model
{
    use HasFactory;

    protected $fillable = [
        'apprenant_id',
        'date',
        'heure_entree',
        'heure_sortie',
        'statut',
    ];

    protected $casts = [
        'date' => 'date',
        'heure_entree' => 'datetime',
        'heure_sortie' => 'datetime',
    ];

    // public function apprenant()
    // {
    //     return $this->belongsTo(Apprenant::class);
    // }
}