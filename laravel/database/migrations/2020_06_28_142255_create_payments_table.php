<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer('operation_number');
            $table->unsignedBigInteger('id_company');            
            $table->string('error_code')->nullable();            
            $table->string('error_message')->nullable();            
            $table->string('authorizacion_code')->nullable();            
            $table->string('card_number')->nullable();            
            $table->string('purchase_amount')->nullable();            
            $table->string('purcase_currency_code')->nullable();            
            $table->string('authentification_ECI')->nullable();            
            $table->string('card_type')->nullable();              
            $table->string('billing_state')->nullable();
            $table->timestamps();
        });
        DB::statement('ALTER TABLE payments CHANGE  operation_number operation_number INT(9) UNSIGNED ZEROFILL NOT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
