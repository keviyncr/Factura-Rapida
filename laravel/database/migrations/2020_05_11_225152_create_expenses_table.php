<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_company');
            $table->foreign('id_company')->references('id')->on('companies');
            $table->unsignedBigInteger('id_provider');
            $table->foreign('id_provider')->references('id')->on('providers');
            $table->string('key',50);
            $table->string('consecutive',20);
            $table->string('ruta');
            $table->float("total_discount")->default(0);
            $table->float("total_tax")->default(0);
            $table->float("total_exoneration")->default(0);
            $table->float("total_invoice", 18, 5)->default(0);
            $table->string('condition');
            $table->integer('e_a',5);
            $table->string('detail_mh',10000)->default("Ninguno");
            $table->string('state');
            $table->string('state_send');
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
        Schema::dropIfExists('expenses');
    }
}
