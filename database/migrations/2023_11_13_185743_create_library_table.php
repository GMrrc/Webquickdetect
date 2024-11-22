<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLibraryTable extends Migration
{
    public function up()
    {
        Schema::create('library', function (Blueprint $table) {
            $table->id('idLibrary');
            $table->string('name');
            $table->foreignId('idUser')->references('idUser')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('libraries');
    }
}
