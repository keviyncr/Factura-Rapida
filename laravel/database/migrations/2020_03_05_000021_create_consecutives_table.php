<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsecutivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consecutives', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_branch_offices');
            $table->foreign('id_branch_offices')->references('id')->on('branch_offices');
            $table->integer("c_fe")->default('1');
            $table->integer("c_nc")->default('1');
            $table->integer("c_nd")->default('1');
            $table->integer("c_fc")->default('1');
            $table->integer("c_fex")->default('1');
            $table->integer("c_te")->default('1');
            $table->timestamps();
        });
        DB::statement('ALTER TABLE consecutives CHANGE  c_fe c_fe INT(10) UNSIGNED ZEROFILL NOT NULL');
        DB::statement('ALTER TABLE consecutives CHANGE  c_nc c_nc INT(10) UNSIGNED ZEROFILL NOT NULL');
        DB::statement('ALTER TABLE consecutives CHANGE  c_nd c_nd INT(10) UNSIGNED ZEROFILL NOT NULL');
        DB::statement('ALTER TABLE consecutives CHANGE  c_fc c_fc INT(10) UNSIGNED ZEROFILL NOT NULL');
        DB::statement('ALTER TABLE consecutives CHANGE  c_fex c_fex INT(10) UNSIGNED ZEROFILL NOT NULL');
        DB::statement('ALTER TABLE consecutives CHANGE  c_te c_te INT(10) UNSIGNED ZEROFILL NOT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consecutives');
    }
}
