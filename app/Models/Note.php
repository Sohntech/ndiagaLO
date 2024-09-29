<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Note extends FirebaseModel
{
    use HasFactory;

    protected $path = 'notes'; 
    protected $fillable = ['apprenant_id', 'module_id', 'note', 'appreciation'];
}