<?php

use Livewire\WithPagination;
use Livewire\Volt\Component;
use App\Models\Vendor;
use App\Models\ProductsCategory;
use function Laravel\Folio\name;

new class extends Component {
    use WithPagination;

    public Vendor $vendor;
    public ?ProductsCategory $category;

    public function mount(): void
    {
        $this->category = null;
    }

    public function with(): array
    {

        return [
            'productsCategories' => $this->vendor->productsCategories()
                ->whereHas('products')
                ->whereHas('products', function($query) {
                    $query->whereHas('groups');
                })
                ->get(),
            'productGroups' => $this->vendor->productGroups()
                ->withCount(["products", "addonsGroups" => function($query) {
                    $query->whereHas("addons");
                }])
                ->whereHas('products', function($query) {
                    $query->whereHas('groups')
                    ->whereNotNull("products_category_id");
                })
                ->when($this->category != null, function($query) {
                    return $query->whereHas('products', function($query) {
                        $query->where('products_category_id', $this->category->id);
                    });
                })
                ->get(),
        ];
    }

    public function selectCategory($categoryId = null): void
    {
        if(!$categoryId) {
            $this->category = null;
            return;
        }

        $category = $this->vendor->productsCategories->find($categoryId);

        $this->category = $category;
    }
} ?>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @volt
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h4 class=" text-white text-2xl mb-6">
                Vendor Details ({{ $vendor->name }})
            </h4>
            <h5 class=" text-white text-xl mb-4">
                Categories
            </h5>

            <div class="flex space-x-4 mb-6">
                <button class="px-4 py-2 bg-blue-500 text-white rounded-full"
                    wire:click="selectCategory(null)">All</button>
                @foreach ($productsCategories as $category)
                <button class="px-4 py-2 bg-blue-500 text-white rounded-full"
                    wire:click="selectCategory({{ $category->id }})">{{$category->name }}</button>
                @endforeach
            </div>
            @foreach ($productGroups as $group)
            <div wire:key="{{ $group->id }}"
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6 text-gray-900 dark:text-gray-100 flex flex-row justify-between">
                    <div>
                        {{ $group->name }}
                        <br>
                        {{ Number::currency($group->products[0]?->price, "NGN") }}
                    </div>

                    <div>
                        @if ($group->products_count == 1 && $group->addons_groups_count == 0 )
                        <button @click="alert(' Item added to cart')"
                            class="px-4 py-2 bg-purple-500 text-white rounded-full">
                            Add to cart
                        </button>
                        @else
                        <a href="/products_groups/{{$group->id}}"
                            class="px-4 py-2 bg-orange-500 text-white rounded-full">
                            View
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endvolt
</x-app-layout>