<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
//use App\Models\User;
use Laravel\Cashier\Billable;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentMethod;
use Stripe\Customer;
use Stripe\PaymentIntent;
use Stripe\StripeClient;


class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }
    public function show1($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show1', compact('product'));
    }

   /* public function charge(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $user = $request->user();
//echo "<pre>";print_r($request->all());exit;
        // Charge the user via Stripe
        $paymentMethod = $request->paymentMethodId;
        $user->charge($product->price * 100, $paymentMethod); // Amount in cents

        return redirect()->route('products.index')->with('success', 'Payment successful!');
    }*/
    public function charge(Request $request, $id)
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->withErrors('You need to log in to make a purchase.');
        }

        $product = Product::findOrFail($id);
        $user = $request->user();

        // Ensure the user has a Stripe customer ID
        if (!$user->stripe_id) {
            try {
                $user->createAsStripeCustomer();
            } catch (ApiErrorException $e) {
                return back()->withErrors(['error' => 'Failed to create Stripe customer: ' . $e->getMessage()]);
            }
        }

        $paymentMethodId = $request->paymentMethodId;
        $name = $request->name;
        $address = $request->address;

        // Create a Stripe client
        $stripe = new StripeClient(env('STRIPE_SECRET'));

        try {
            // Create a PaymentIntent
            $paymentIntent = $stripe->paymentIntents->create([
                'amount' => $product->price * 100, // Amount in cents
                'currency' => 'usd',
                'payment_method' => $paymentMethodId,
                'confirmation_method' => 'manual',
                'confirm' => true,
                'description' => 'Purchase of ' . $product->name . ' - ' . $product->description,
                'receipt_email' => $user->email,
                'shipping' => [
                    'name' => $name,
                    'address' => [
                        'line1' => $address,
                        'city' => 'Some City',
                        'state' => 'Some State',
                        'postal_code' => '123456',
                        'country' => 'IN',
                    ],
                ],
            ]);

            // Handle the response
            if ($paymentIntent->status === 'succeeded') {
                return redirect()->route('products.index')->with('success', 'Payment successful!');
            } else {
                return back()->withErrors(['error' => 'Payment failed: ' . $paymentIntent->status]);
            }
        } catch (ApiErrorException $e) {
            return back()->withErrors(['error' => 'Payment failed: ' . $e->getMessage()]);
        }
    }
   

public function chargeworks(Request $request, $id)
{
    if (!auth()->check()) {
        return redirect()->route('login')->withErrors('You need to log in to make a purchase.');
    }

    $product = Product::findOrFail($id);
    $user = $request->user();

    if (!$user->stripe_id) {
        try {
            $user->createAsStripeCustomer();
        } catch (ApiErrorException $e) {
            return back()->withErrors(['error' => 'Failed to create Stripe customer: ' . $e->getMessage()]);
        }
    }

    $paymentMethodId = $request->paymentMethodId;

    try {
        $user->addPaymentMethod($paymentMethodId);
    } catch (\Exception $e) {
        return back()->withErrors(['error' => 'Failed to attach payment method: ' . $e->getMessage()]);
    }

    try {
        $user->charge($product->price * 100, $paymentMethodId);
    } catch (\Exception $e) {
        return back()->withErrors(['error' => 'Payment failed: ' . $e->getMessage()]);
    }

    return redirect()->route('products.index')->with('success', 'Payment successful!');
}
public function charge1(Request $request, $id)
    {
        // Check if the user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->withErrors('You need to log in to make a purchase.');
        }

        $product = Product::findOrFail($id);
        $user = $request->user();

        // Ensure the user has a Stripe customer ID
        if (!$user->stripe_id) {
            try {
                $user->createAsStripeCustomer();
            } catch (ApiErrorException $e) {
                return back()->withErrors(['error' => 'Failed to create Stripe customer: ' . $e->getMessage()]);
            }
        }

        $paymentMethodId = $request->paymentMethodId;

        try {
            // Attach the payment method to the user
            $user->addPaymentMethod($paymentMethodId);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to attach payment method: ' . $e->getMessage()]);
        }

        // Charge the user via Stripe
        try {
            $user->charge($product->price * 100, $paymentMethodId, [
                'description' => 'Purchase of ' . $product->name . ' - ' . $product->description,
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Payment failed: ' . $e->getMessage()]);
        }

        return redirect()->route('products.index')->with('success', 'Payment successful!');
    }
    
}
