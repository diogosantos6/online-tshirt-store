<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;


class TshirtImage extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'costumer_id', 'category_id', 'name', 'description',
        'image_url', 'extra_info',
    ];

    // protected function fullImageUrl(): Attribute
    // {
    //     return Attribute::make(
    //         get: function () {
    //             return $this->image_url ? asset('storage/tshirt_images/' . $this->image_url) :
    //                 asset('/img/plain_white.png');
    //         },
    //     );
    // }

    protected function fullImageUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                // if ($this->customer_id != null && $this->image_url /* && $this->customer->id == auth()->user()->id */) {
                //     $privatePath = storage_path('app/tshirt_images_private/' . $this->image_url);
                //     if (!File::exists($privatePath)) {
                //         abort(404);
                //     }
                //     $file = File::get($privatePath);
                //     return "data:image/png;base64," . base64_encode($file);
                // }

                // Caso a tshirt faça parte do catálogo	então retorna diretamente a imagem a partir do public folder...
                // Se a tshirt não fizer parte do catálogo então faz o route para o controller que retorna a imagem
                if ($this->image_url && $this->customer_id == null) {
                    return asset('storage/tshirt_images/' . $this->image_url);
                }
                if ($this->image_url && !$this->trashed()) {
                    return route('tshirt_images.minha', ['image_url' => $this->image_url]);
                }
                // return $this->image_url && $this->customer_id == null ? asset('storage/tshirt_images/' . $this->image_url) :
                //     route('tshirt_images.minha', ['image_url' => $this->image_url]);
            }
        );
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class)->withTrashed();
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'tshirt_image_id');
    }
}
