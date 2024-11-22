<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $table = 'videos';
    protected $primaryKey = 'idVideo';
    protected $fillable = ['title', 'format', 'size', 'path', 'data', 'idLibrary'];

    public function library()
    {
        return $this->belongsTo(Library::class, 'idLibrary');
    }
}
