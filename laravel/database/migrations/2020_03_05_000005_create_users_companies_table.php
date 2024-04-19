<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_company');
            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('id_company')->references('id')->on('companies');
            $table->enum('active', [0,1])->default("1");
            $table->enum('roll', ['Super Administrador','Administrador','Colaborador']);
            $table->timestamps();
        });
         DB::statement('ALTER TABLE users_companies ADD CONSTRAINT company_user_unique UNIQUE (id_user, id_company);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_companies');
    }
}
