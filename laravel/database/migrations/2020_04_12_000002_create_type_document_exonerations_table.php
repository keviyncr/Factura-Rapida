<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypeDocumentExonerationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_document_exonerations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('code');            
            $table->string("document");
            $table->timestamps();
        });
         DB::statement('ALTER TABLE type_document_exonerations CHANGE  code code INT(3) UNSIGNED ZEROFILL NOT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('type_document_exonerations');
    }
}
