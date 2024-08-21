<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsGroup extends Model
{
    use HasFactory;

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, "products_groups_products");
    }

    public function addonsGroups()
    {
        return $this->belongsToMany(ProductAddonsGroup::class, "product_addons");
    }
}
