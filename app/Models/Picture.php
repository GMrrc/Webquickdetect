<?php

namespace App\Models;

use Database\Factories\LibrairieFactory;
use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    protected $table = 'pictures'; 
    protected $primaryKey = 'idPicture';

    protected $fillable = [
        'title',
        'format',
        'size',
        'path',
        'idLibrary',
        'data',
        'dataIA'
    ];

    public function library()
    {
        return $this->belongsTo(Library::class, 'idLibrary', 'idLibrary');
    }

}
