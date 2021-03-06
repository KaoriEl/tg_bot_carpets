<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableCustomerCarpets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_carpets', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('tg_user_id')->unsigned();
            $table->foreign('tg_user_id')->references('id')->on('tg_users');
            $table->string('id_deals');
            $table->text('photo');
            $table->string('comment')->nullable();
            $table->string('status');
            $table->string('media_group_id')->nullable();
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
        Schema::dropIfExists('customer_carpets');
    }
}
