<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_company');
            $table->foreign('id_company')->references('id')->on('companies');
            $table->unsignedBigInteger('id_client');
            $table->foreign('id_client')->references('id')->on('clients');
            $table->string('key',50);
            $table->string('consecutive',20);
            $table->string('ruta');
            $table->float("total_discount")->default(0);
            $table->float("total_tax")->default(0);
            $table->float("total_exoneration")->default(0);
            $table->float("total_invoice", 18, 5)->default(0);
            $table->integer('e_a',5);
            $table->string('detail_mh',10000);
            $table->string('answer_mh');
            $table->string('state_send');
            $table->timestamps();
        });
        DB::statement('ALTER TABLE documents CHANGE  e_a e_a INT(6) UNSIGNED ZEROFILL NOT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documents');
    }
}

