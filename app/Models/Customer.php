<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    use HasFactory, SoftDeletes;
    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        //'id', //TODO
        'id',
        'nif',
        'address',
        'default_payment_type',
        'default_payment_ref',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id')->withTrashed();
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function tshirtImages(): HasMany
    {
        return $this->hasMany(TshirtImage::class);
    }
}
