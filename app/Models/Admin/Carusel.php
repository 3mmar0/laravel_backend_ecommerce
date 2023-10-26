<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carusel extends Model
{
    use HasFactory;

    protected $fillable = ['image'];
}
