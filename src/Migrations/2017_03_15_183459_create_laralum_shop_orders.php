<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaralumShopOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('laralum_shop_orders')) {
            Schema::create('laralum_shop_orders', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('status_id');
                $table->integer('user_id');
                $table->decimal('tax_percentage_on_buy', 5, 2);
                $table->string('shipping_name')->nullable();
                $table->string('shipping_adress')->nullable();
                $table->string('shipping_zip')->nullable();
                $table->string('shipping_state')->nullable();
                $table->string('shipping_city')->nullable();
                $table->string('shipping_country')->nullable();
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
        Schema::dropIfExists('laralum_shop_orders');
    }
}
