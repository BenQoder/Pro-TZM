<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductAddonsGroup;
use App\Models\ProductsCategory;
use App\Models\ProductsGroup;
use App\Models\User;
use App\Models\Vendor;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $vendors =  Vendor::factory()
            ->count(50)
            ->create();

        $vendors->each(function ($vendor) {

            // Attach Products

            Product::factory(random_int(200, 500), [
                'vendor_id' => $vendor->id,
            ])->create();

            // Attach Groups
            ProductsGroup::factory(random_int(3, 10), [
                'vendor_id' => $vendor->id,
            ])->create();

            // Attach Addons Groups
            ProductAddonsGroup::factory(random_int(3, 5), [
                'vendor_id' => $vendor->id,
            ])->create();

            // Attach Categories
            ProductsCategory::factory(random_int(3, 5), [
                'vendor_id' => $vendor->id,
            ])->create();

            $vendor->productGroups->each(function ($group) use ($vendor) {

                $group->products()->attach($vendor->products->random(random_int(1, 5)));

                $group->addonsGroups()->attach($vendor->addonsGroups->random(
                    random_int(0, 3)
                ), [
                    "vendor_id" => $vendor->id,
                ]);
            });

            $vendor->products->random(
                random_int(100, 150)
            )->each(function ($product) use ($vendor) {
                $product->update([
                    'products_category_id' => $vendor->productsCategories->random()->id,
                ]);
            });

            $vendor->addonsGroups->each(function ($group) use ($vendor) {
                $group->addons()->attach($vendor->products->random(
                    random_int(1, 5)
                ));
            });
        });
    }
}
