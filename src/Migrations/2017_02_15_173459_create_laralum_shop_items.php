<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLaralumShopItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('laralum_shop_items')) {
            Schema::create('laralum_shop_items', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->text('description');
                $table->integer('price');
                $table->integer('units')->nullable();
                $table->integer('category_id')->nullable();
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
        Schema::dropIfExists('laralum_shop_items');
    }
}
