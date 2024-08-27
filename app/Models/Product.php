<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function groups()
    {
        return $this->belongsToMany(ProductsGroup::class, "products_groups_products");
    }

    public function addons_groups()
    {
        return $this->belongsToMany(ProductAddonsGroup::class);
    }
}
