<?php

use App\Models\Product;
use App\Models\ProductsGroup;
use App\Models\Vendor;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products_groups', function (Blueprint $table) {
            $table->id()->startingValue(1000);
            $table->string('name');
            $table->foreignIdFor(Vendor::class)->constrained();
            $table->timestamps();
        });

        Schema::create(
            'products_groups_products',
            function (Blueprint $table) {
                $table->id()->startingValue(1000);
                $table->foreignIdFor(ProductsGroup::class)->constrained();
                $table->foreignIdFor(Product::class)->constrained();
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_groups');
        Schema::dropIfExists('products_groups_products');
    }
};
