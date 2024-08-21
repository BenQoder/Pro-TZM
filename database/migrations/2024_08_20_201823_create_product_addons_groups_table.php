<?php

use App\Models\Product;
use App\Models\ProductAddonsGroup;
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
        Schema::create('product_addons_groups', function (Blueprint $table) {
            $table->id()->startingValue(1000);
            $table->string('name');
            $table->foreignIdFor(Vendor::class)->constrained();
            $table->timestamps();
        });

        Schema::create('product_addons', function (Blueprint $table) {
            $table->id()->startingValue(1000);
            $table->foreignIdFor(Vendor::class)->constrained();
            $table->foreignIdFor(
                ProductsGroup::class,
            )->constrained();
            $table->foreignIdFor(ProductAddonsGroup::class)->constrained();
        });

        Schema::create('product_addons_group_product', function (Blueprint $table) {
            $table->id()->startingValue(1000);
            $table->foreignIdFor(ProductAddonsGroup::class)->constrained();
            $table->foreignIdFor(Product::class)->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_addons_groups');
        Schema::dropIfExists('product_addons');
    }
};
