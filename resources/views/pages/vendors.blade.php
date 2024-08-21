<?php

use Livewire\WithPagination;
use Livewire\Volt\Component;
use App\Models\Vendor;

new class extends Component {
    use WithPagination;

    public function with(): array
    {
        return [
            'vendors' => Vendor::withCount([
                "products",
            ])->get(),
        ];
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
                Vendor List
            </h4>
            @foreach ($vendors as $vendor)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6 text-gray-900 dark:text-gray-100  flex flex-row justify-between">
                    <div>
                        <span>
                            {{ $vendor->name }}
                        </span>
                        <br>
                        <span class="text-gray-500 dark:text-gray-400">
                            {{ $vendor->products_count }} Products
                        </span>
                    </div>
                    <div>
                        <a href="/vendors/{{ $vendor->id }}" class="px-4 py-2 bg-blue-500 text-white rounded-full">
                            Visit Vendor
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endvolt
</x-app-layout>