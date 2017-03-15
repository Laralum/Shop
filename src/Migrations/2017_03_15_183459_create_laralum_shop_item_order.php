<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLaralumShopItemOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('laralum_shop_item_order')) {
            Schema::create('laralum_shop_item_order', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('order_id');
                $table->integer('item_id');
                $table->integer('units');
                $table->text('item_on_buy');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laralum_shop_item_order');
    }
}
