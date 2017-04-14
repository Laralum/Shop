<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Laralum\Shop\Models\Settings;

class CreateLaralumShopSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('laralum_shop_settings')) {
            Schema::create('laralum_shop_settings', function (Blueprint $table) {
                $table->increments('id');
                $table->string('currency');
                $table->integer('default_status');
                $table->integer('paid_status');
                $table->string('public_prefix');
                $table->decimal('tax_percentage', 5, 2);
                $table->boolean('emergency');
                $table->timestamps();
            });
            Settings::create([
                'currency'          => 'EUR',
                'default_status'    => 1,
                'paid_status'       => 2,
                'public_prefix'     => 'shop',
                'tax_percentage'    => 0,
                'emergency'         => false,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laralum_shop_settings');
    }
}
