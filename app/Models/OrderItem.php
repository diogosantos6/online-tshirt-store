<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'order_id', 'tshirt_image_id', 'color_code', 'size',
        'qty', 'unit_price', 'sub_total'
    ];

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_code', 'code')->withTrashed();
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function tshirtImage(): BelongsTo
    {
        return $this->belongsTo(TshirtImage::class, 'tshirt_image_id')->withTrashed();
    }
}
