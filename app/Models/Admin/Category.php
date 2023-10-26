<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        "parent_id",
        "name",
        "slug",
        "disc",
        "image",
        "status",
    ];

    // Relations
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
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
}
