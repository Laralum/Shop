<?php

namespace Laralum\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    public $table = 'laralum_shop_settings';
    public $fillable = [
        'currency', 'default_status', 'paid_status',
        'public_prefix', 'tax_percentage', 'emergency',
    ];

    /**
     * Return the default status.
     */
    public function defaultStatus()
    {
        return $this->belongsTo(Status::class, 'default_status');
    }

    /**
     * Return the default status.
     */
    public function paidStatus()
    {
        return $this->belongsTo(Status::class, 'paid_status');
    }

    /**
     * Return all the shop currencies.
     */
    public function currencies()
    {
        return json_decode(file_get_contents(__DIR__ . "/../Currencies.json"), true);
    }
}
