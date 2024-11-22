<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePicturesTable extends Migration
{
    public function up()
    {
        Schema::create('pictures', function (Blueprint $table) {
            $table->id('idPicture');
            $table->string('title');
            $table->enum('format', ['jpg', 'png', 'jpeg']);
            $table->float('size')->check('poids < 2');
            $table->string('path');
            $table->unsignedBigInteger('idLibrary');
            $table->foreign('idLibrary')->references('idLibrary')->on('library')->onDelete('cascade');
            $table->binary('data')->nullable();
            $table->json('dataIA')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pictures');
    }
}
