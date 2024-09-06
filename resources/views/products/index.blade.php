@extends('layouts.app')

@section('content')
    <div class="container mx-auto">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($products as $product)
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="p-4">
                        <h5 class="font-bold text-xl mb-2">{{ $product->name }}</h5>
                        <p class="text-gray-700 mb-4">${{ number_format($product->price, 2) }}</p>
                        <a href="{{ route('products.show', $product->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Buy Now</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
