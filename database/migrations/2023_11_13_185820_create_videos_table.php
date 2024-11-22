<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id('idVideo');
            $table->string('title');
            $table->string('format')->check("format IN ('mp4')");
            $table->double('size')->check('poids > 10000000');
            $table->string('path');
            $table->string('data');
            $table->foreignId('idLibrary')->references('idLibrary')->on('library')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('videos');
    }
}
