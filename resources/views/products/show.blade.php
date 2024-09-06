@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <div class="max-w-lg mx-auto bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-4">{{ $product->name }}</h2>
                <p class="text-gray-700 mb-4">{{ $product->description }}</p>
                <p class="text-lg font-semibold mb-6">Price: ${{ number_format($product->price, 2) }} USD</p>

                <form action="{{ route('products.charge', $product->id) }}" method="POST" id="payment-form" class="space-y-4">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                    </div>

                    <div class="form-group">
                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                        <input type="text" name="address" id="address" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                    </div>

                    <div class="form-group">
                        <label for="card-element" class="block text-sm font-medium text-gray-700">Credit or debit card</label>
                        <div id="card-element" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <!-- A Stripe Element will be inserted here -->
                        </div>
                        <div id="card-errors" role="alert" class="text-red-500 mt-2"></div>
                    </div>

                    <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Pay ${{ number_format($product->price, 2) }} USD
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        var stripe = Stripe('{{ config('cashier.key') }}');
        var elements = stripe.elements();
        var card = elements.create('card');
        card.mount('#card-element');

        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            stripe.createPaymentMethod({
                type: 'card',
                card: card,
            }).then(function(result) {
                if (result.error) {
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    var hiddenInput = document.createElement('input');
                    hiddenInput.setAttribute('type', 'hidden');
                    hiddenInput.setAttribute('name', 'paymentMethodId');
                    hiddenInput.setAttribute('value', result.paymentMethod.id);
                    form.appendChild(hiddenInput);

                    // Add customer information
                    var nameInput = document.createElement('input');
                    nameInput.setAttribute('type', 'hidden');
                    nameInput.setAttribute('name', 'name');
                    nameInput.setAttribute('value', document.getElementById('name').value);
                    form.appendChild(nameInput);

                    var addressInput = document.createElement('input');
                    addressInput.setAttribute('type', 'hidden');
                    addressInput.setAttribute('name', 'address');
                    addressInput.setAttribute('value', document.getElementById('address').value);
                    form.appendChild(addressInput);

                    form.submit();
                }
            });
        });
    </script>
@endsection
