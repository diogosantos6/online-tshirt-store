<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Color extends Model
{
    use HasFactory, SoftDeletes;

    // Estava a dar problemas devido ao facto de não ter o updated_at
    public $timestamps = false;

    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'code', 'name',
    ];

    protected function fullImageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                // return $this->code ? asset('storage/tshirt_base/' . $this->code . '.jpg') :
                //     asset('/img/plain_white.png');
                if ($this->code != null && !$this->trashed()) {
                    return asset('storage/tshirt_base/' . $this->code . '.jpg');
                }
                return asset('/img/plain_white.png');
            },
        );
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'color_code', 'code');
    }
}
