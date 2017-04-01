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
                $table->string('color');
                $table->timestamps();
            });

            $status = [
                'Pending'   => '#FF9800',
                'Processed' => '#2196F3',
                'Shipped'   => '#9C27B0',
                'Completed' => '#4CAF50',
                'Canceled'  => '#F44336',
            ];

            foreach($status as $name => $color) {
                Status::create([
                    'name' => $name,
                    'color' = $color,
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
