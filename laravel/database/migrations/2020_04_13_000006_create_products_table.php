<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_company');
            $table->foreign('id_company')->references('id')->on('companies');            
            $table->string("description", 200);
            $table->string("tariff_heading",200)->default("Ninguna");
            $table->unsignedBigInteger('id_sku');
            $table->foreign('id_sku')->references('id')->on('skuses');
            $table->json("ids_discounts")->nullable();
            $table->json("ids_taxes")->nullable();
            $table->float("tax_base", 18, 5)->default(0);
            $table->float("total_discount")->default(0);
            $table->float("total_tax")->default(0);
            $table->float("total_exoneration")->default(0);
            $table->float("price_unid", 18, 5)->default(0);
            $table->float("total_price", 18, 5)->default(0);
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
        Schema::dropIfExists('products');
    }
}
