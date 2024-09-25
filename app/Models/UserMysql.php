<?php
namespace App\Models;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserMysql extends Authenticatable
{
    use HasApiTokens, Notifiable;
    
    protected $connection = 'mysql';
    protected $fillable = [
        'nom', 
        'prenom', 
        'adresse', 
        'email', 
        'password', 
        'telephone', 
        'photo', 
        'role_id', 
        'fonction', 
        'status'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $table = 'users';

    public function role(): BelongsTo
    {
        return $this->belongsTo(RoleMysql::class);
    }
}