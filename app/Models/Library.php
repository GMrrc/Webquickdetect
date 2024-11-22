<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Library extends Model
{
    protected $table = 'library';
    protected $primaryKey = 'idLibrary';
    protected $fillable = ['name', 'idUser'];

    public function user()
    {
        return $this->belongsTo(User::class, 'idUser');
    }

    public function pictures()
    {
        return $this->hasMany(Picture::class, 'idLibrary');
    }

    public function videos()
    {
        return $this->hasMany(Video::class, 'idLibrary');
    }
}
