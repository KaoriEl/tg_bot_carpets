<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableShelves extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shelves', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tg_user_id')->unsigned();
            $table->foreign('tg_user_id')->references('id')->on('tg_users');
            $table->string('id_deals');
            $table->string('comment');
            $table->string('status');
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
        Schema::dropIfExists('shelves');
    }
}
