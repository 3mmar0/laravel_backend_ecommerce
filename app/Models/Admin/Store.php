<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "slug",
        "disc",
        "logo",
        "cover",
        "status",
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
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
    }
    public function scopeActive(Builder $builder)
    {
        return $builder->where('status', '=', 'active');
    }
}
