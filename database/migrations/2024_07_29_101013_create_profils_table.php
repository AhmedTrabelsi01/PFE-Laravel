<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profils', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('domain')->nullable();
            $table->string('img');
            $table->string('location')->nullable();
            $table->string('birth_date')->nullable();
            $table->string('profession')->nullable();
            $table->string('linkedin')->nullable();

            $table->integer('djACA')->default(0);
            $table->integer('djMD')->default(0);
            $table->integer('djJU')->default(0);
            $table->integer('djFab')->default(0);
            $table->integer('djOUt')->default(0);


            $table->integer('age')->nullable();
            $table->unsignedBigInteger('gender_id');
            $table->foreign('gender_id')->references('id')->on('genders')->cascade();
            $table->unsignedBigInteger('role_id');
            $table->foreign('role_id')->references('id')->on('roles')->cascade();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascade();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profils');
    }
}
