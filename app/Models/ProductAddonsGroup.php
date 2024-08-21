<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAddonsGroup extends Model
{
    use HasFactory;

    public function addons()
    {
        return $this->belongsToMany(Product::class, 'product_addons_group_product');
    }
}
