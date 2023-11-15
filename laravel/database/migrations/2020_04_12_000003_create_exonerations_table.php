<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExonerationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exonerations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_company');            
            $table->foreign('id_company')->references('id')->on('companies');
            $table->string("description");
            $table->unsignedBigInteger('id_type_document_exoneration');            
            $table->foreign('id_type_document_exoneration')->references('id')->on('type_document_exonerations');
            $table->string("document_number", 40);
            $table->string("institutional_name", 160);
            $table->dateTime("date");
            $table->integer("exemption_percentage");
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
        Schema::dropIfExists('exonerations');
    }
}
