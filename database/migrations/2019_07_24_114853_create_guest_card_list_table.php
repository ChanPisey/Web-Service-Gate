<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuestCardListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guest_card_list', function (Blueprint $table) {
            $table->increments('id');
            $table->string('card_code');
            $table->string('card_number');
            $table->string('gate_direction');
            $table->string('guest_type');
            $table->integer('user_id')->foreign('user_id')->references('id')->on('users');
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('guest_card_list');
    }
}
