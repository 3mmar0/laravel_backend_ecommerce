<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        "store_id",
        "category_id",
        "name",
        "slug",
        "disc",
        "image",
        "price",
        "compare_price",
        "rating",
        "options",
        "type",
        "status",
    ];

    protected static function booted()
    {
        static::addGlobalScope('store', function (Builder $builder) {
            $user = Auth::user();
            if ($user && $user->store_id && $user->role != "admin") {
                # code...
                $builder->where('store_id', $user->store_id);
            }
        });
    }

    // relations
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function tags()
    {
        return $this->belongsToMany(
            Tag::class,
            'product_tag',
            'product_id',
            'tag_id',
            'id',
            'id',
        );
    }
    // scopes
    public function scopeFilter(Builder $builder, $filters)
    {
        if ($filters['name'] ?? false) {
            $builder->where('name', 'LIKE', "%{$filters['name']}%");
        }
        if ($filters['status'] ?? false) {
            $builder->where('status', '=', $filters['status']);
        }
        if ($filters['category_id'] ?? false) {
            $builder->where('category_id', '=', $filters['category_id']);
        }
        if ($filters['price'] ?? false) {
            $builder->where('price', '<=', $filters['price']);
        }
        if ($filters['rating'] ?? false) {
            $builder->where('rating', '<=', $filters['rating']);
        }
    }
}
