@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>{{ $product->name }}</h2>
        <p>{{ $product->description }}</p>
        <p>Price: {{ $product->price }} USD</p>

        <form action="{{ route('products.charge1', $product->id) }}" method="POST" id="payment-form">
            @csrf
            <div class="form-group">
                <label for="card-element">Credit or debit card</label>
                <div id="card-element">
                    <!-- A Stripe Element will be inserted here -->
                </div>
                <div id="card-errors" role="alert"></div>
            </div>
            <button type="submit" class="btn btn-primary">Pay {{ $product->price }} USD</button>
        </form>
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
                    form.submit();
                }
            });
        });
    </script>
@endsection
