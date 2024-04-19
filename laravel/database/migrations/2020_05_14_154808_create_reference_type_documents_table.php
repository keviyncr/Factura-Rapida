<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferenceTypeDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reference_type_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('code');
            $table->string("document");
            $table->timestamps();
        });
        DB::statement('ALTER TABLE reference_type_documents CHANGE  code code INT(2) UNSIGNED ZEROFILL NOT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reference_type_documents');
    }
}
