<?php

use Livewire\WithPagination;
use Livewire\Volt\Component;
use App\Models\product;
use App\Models\ProductsGroup;
use App\Models\ProductsCategory;
use function Laravel\Folio\name;
use function Livewire\Volt\{mount, state, computed};

state('group');

state(["option" => null]);

state(["selectedAddons" => []]);

mount(function (ProductsGroup $group) {
   $this->group = $group->load([
        'products',
        'addonsGroups.addons',
   ]);

   if ($this->group->products->count() === 1) {
       $this->option = $this->group->products->first();
   }
});

$selectOption = function (Product $product) {
    $this->option = $product;
};

$onAddAddon = function (ProductsGroup $group, Product $addon) {
    $selectedAddonsCount = $this->selectedAddons[$group->id][$addon->id] ?? 0;

    $this->selectedAddons[$group->id][$addon->id] = $selectedAddonsCount + 1;
};

$onRemoveAddon = function (ProductsGroup $group, Product $addon) {
    $selectedAddonsCount = $this->selectedAddons[$group->id][$addon->id] ?? 0;

    if ($selectedAddonsCount > 0) {
        $this->selectedAddons[$group->id][$addon->id] = $selectedAddonsCount - 1;
    }
};

$getTotalPrice = computed(function () {
    $totalPrice = 0;

    if ($this->option) {
        $totalPrice += $this->option->price;
    }

    foreach ($this->selectedAddons as $group => $addons) {
        foreach ($addons as $addon => $count) {
            $addon = Product::find($addon);

            $totalPrice += $addon->price * $count;
        }
    }

    return Number::currency($totalPrice, "NGN");
});

?>

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
                Product Group ({{ $group->name }}) <button @click="alert(' Item added to cart')"
                    class=" px-4 py-2 bg-blue-500 text-white rounded-full float-right">
                    Add to cart ({{ $this->getTotalPrice }})
                </button>
            </h4>
            <h3 class=" text-white text-xl mb-6">
                Product Options
            </h3>
            @foreach ($group->products as $product)
            <div wire:click="selectOption({{ $product->id }})" wire:key="{{ $product->id }}" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-4 flex flex-col justify-between
                {{ $product->is($option) ? " border-2 border-orange-500" : "" }} ">
                <div class=" p-6 text-gray-900 dark:text-gray-100">
                {{ $product->name }}
                <br>
                {{ Number::currency($product->price, "NGN") }}
            </div>
        </div>
        @endforeach

        @if ($group->addonsGroups->count())
        <br>
        <br>
        <h3 class=" text-white text-xl mb-6">
            Product Group Addons
        </h3>


        @foreach ($group->addonsGroups as $addonsGroup)
        <div wire:key="{{ $addonsGroup->id }}"
            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-4">
            <div class="p-6 text-2xl text-gray-900 dark:text-gray-100">
                <a href="">
                    {{ $addonsGroup->name }}
                </a>
            </div>

            @foreach ($addonsGroup->addons as $addon)
            <div wire:key="{{ $addon->id }}"
                class="bg-white dark:bg-gray-600 overflow-hidden shadow-sm sm:rounded-lg mb-4 mx-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="p-6 text-gray-900 dark:text-gray-100 flex flex-row justify-between">
                        <a href="">
                            {{ $addon->name }}
                            <br>
                            {{ Number::currency($product->price, "NGN") }}
                        </a>
                        <div class=" ">
                            @if ($selectedAddons[$addonsGroup->id][$addon->id] ?? 0 > 0)
                            <button class="px-4 py-2 bg-red-500 text-white rounded-full"
                                wire:click='onRemoveAddon({{ $addonsGroup->id }}, {{ $addon->id }})'>
                                -
                            </button>
                            <span class="py-2 text-white rounded-full text-2xl mx-3">
                                {{ $selectedAddons[$addonsGroup->id][$addon->id] ?? 0 }}
                            </span>
                            @endif
                            <button class="px-4 py-2 bg-green-500 text-white rounded-full"
                                wire:click='onAddAddon({{ $addonsGroup->id }}, {{ $addon->id }})'>
                                +
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
        @endif

    </div>
    </div>
    @endvolt
</x-app-layout>