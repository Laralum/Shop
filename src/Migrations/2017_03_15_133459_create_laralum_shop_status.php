<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Laralum\Shop\Models\Status;

class CreateLaralumShopStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('laralum_shop_status')) {
            Schema::create('laralum_shop_status', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->timestamps();
            });

            $status = ['Pending', 'Processed', 'Shipped', 'Completed', 'Canceled'];
            foreach($status as $name) {
                Status::create([
                    'name' => $name,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laralum_shop_status');
    }
}
