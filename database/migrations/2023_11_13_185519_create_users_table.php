<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('idUser');
            $table->string('name');
            $table->string('surname');
            $table->date('dateOfBirth');
            $table->string('role')->check("role IN ('admin', 'user')");
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('verify_email')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}

