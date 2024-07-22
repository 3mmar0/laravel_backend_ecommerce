<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'first_name',
        'last_name',
        'phone',
        'address',
        'country',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
