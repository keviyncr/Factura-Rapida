<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_card', 12);
            $table->unsignedBigInteger('type_id_card');
            $table->foreign('type_id_card')->references('id')->on('type_id_cards');
            $table->string('name_company', 80);
            $table->string('user_mh');
            $table->string('pass_mh');
            $table->string('cryptographic_key');
            $table->string('pin');
            $table->string('logo_url')->nullable();
            $table->enum('plan', [1,2])->default("1");
            $table->date('expirationDate');
            $table->integer('docs');
            $table->enum('active', [0,1])->default("0");
            
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
        Schema::dropIfExists('companies');
    }
}
