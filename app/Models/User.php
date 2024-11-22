<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\User as AuthenticatableUser;
use Illuminate\Notifications\Notifiable;
use App\Observers\PasswordChangeObserver;



class User extends AuthenticatableUser implements Authenticatable, MustVerifyEmail
{
    use Notifiable;

    // Nom de la table dans la base de données
    protected $table = 'users';

    // Clé primaire de la table
    protected $primaryKey = 'idUser';

    // Les attributs qui sont mass assignable
    protected $fillable = ['name', 'surname', 'dateOfBirth', 'role', 'email', 'password'];

    // Les attributs cachés pour les tableaux
    protected $hidden = ['password', 'remember_token'];

    // Les attributs qui doivent être castés en types natifs
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function library()
    {
        return $this->hasMany(Library::class, 'idUser');
    }

    // Getter pour le mot de passe (si vous utilisez un nom de colonne différent dans la base de données)
    public function getAuthPassword()
    {
        return $this->password;
    }

    public function canDeleteUser(User $user)
    {
        return $this->isAdmin() || $this->id === $user->id;
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public static function booted()
    {
        static::observe(new PasswordChangeObserver());
    }

    public function sendPasswordResetNotification($token)   
    {
        $url = 'https://webquickdetect.gmrrc.fr/reset_password/' . $token;
        $this->notify(new ResetPasswordNotification($url));
    }

    public static function findOr($id, $callback)
    {
        $user = self::find($id);

        if (!$user) {
            return call_user_func($callback);
        }

        return $user;
    }

}

