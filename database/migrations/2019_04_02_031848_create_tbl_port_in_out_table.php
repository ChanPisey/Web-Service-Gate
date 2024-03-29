<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPortInOutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_port_in_out', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('source_id');
            $table->integer('unit_id');
            $table->integer('nationality_id');
            $table->integer('identity_id');
            $table->integer('real_image_id');
            $table->integer('card_reader_id');
            $table->integer('user_id');
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
        Schema::dropIfExists('tbl_port_in_out');
    }
}
