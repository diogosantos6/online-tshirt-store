<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Price extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'unit_price_catalog',
        'unit_price_own',
        'unit_price_catalog_discount',
        'unit_price_own_discount',
        'qty_discount'
    ];

    public function getPrice($customer, $qty)
    {
        $totalPrice = 0;
        $aux = 0;
        // Customer is the owner of the image ($customer is not null)
        if ($customer) {
            if ($qty >= $this->qty_discount) {
                $aux = $this->unit_price_own * $qty;
                $totalPrice = $aux - ($aux * ($this->unit_price_own_discount / 100));
            } else {
                $totalPrice = $this->unit_price_own * $qty;
            }
        } else {
            // Customer is not the owner of the image
            if ($qty >= $this->qty_discount) {
                $aux = $this->unit_price_catalog * $qty;
                $totalPrice = $aux - ($aux * ($this->unit_price_catalog_discount / 100));
            } else {
                $totalPrice = $this->unit_price_catalog * $qty;
            }
        }
        return $totalPrice;
    }
}
