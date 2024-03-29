<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblIdentifyCardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_identify_card', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('port_id');
            $table->string('card_id', 255);
            $table->string('name', 255);
            $table->string('gender', 255);
            $table->string('place', 255);
            $table->string('address', 255);
            $table->string('phone', 20);
            $table->string('url_image', 100);
            $table->date('dbo');
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
        Schema::dropIfExists('tbl_identify_card');
    }
}
