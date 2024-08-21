<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function productGroups()
    {
        return $this->hasMany(ProductsGroup::class);
    }

    public function productsCategories()
    {
        return $this->hasMany(ProductsCategory::class);
    }

    public function addonsGroups()
    {
        return $this->hasMany(ProductAddonsGroup::class);
    }
}
