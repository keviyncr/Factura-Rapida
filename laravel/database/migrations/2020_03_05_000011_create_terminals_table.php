<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTerminalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('terminals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_company');
            $table->unsignedBigInteger('id_branch_office');
            $table->foreign('id_company')->references('id')->on('companies');
            $table->foreign('id_branch_office')->references('id')->on('branch_offices');
            $table->string('number');
            $table->enum('active', [0,1]);
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
        Schema::dropIfExists('terminals');
    }
}
