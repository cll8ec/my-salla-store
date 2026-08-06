<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domains\Catalog\Enums\SyncStatus;
use App\Domains\Commerce\Enums\OrderStatus;
use App\Domains\Commerce\Enums\PaymentMethod;
use App\Domains\Commerce\Enums\PaymentStatus;
use App\Domains\Commerce\Enums\ShippingStatus;
use App\Domains\Commerce\Models\Customer;
use App\Domains\Commerce\Models\Order;
use App\Domains\Commerce\Models\OrderItem;
use App\Shared\Salla\SallaClient;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        return view('customer.checkout');
    }

    public function place(Request $request, SallaClient $sallaClient)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'address' => 'required|string',
            'postal_code' => 'nullable|string|max:20',
            'cart_items' => 'required|string',
        ]);

        $cartItems = json_decode($validated['cart_items'], true);

        if (empty($cartItems)) {
            return back()->withErrors(['cart' => 'السلة فارغة']);
        }

        // Create the Customer record or update it locally
        $names = explode(' ', $validated['name'], 2);
        $firstName = $names[0] ?? '';
        $lastName = $names[1] ?? '';

        $customer = Customer::updateOrCreate(
            ['email' => $validated['email']],
            [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'mobile' => $validated['phone'],
                'city' => $validated['city'],
                'addresses' => [
                    [
                        'city' => $validated['city'],
                        'street' => $validated['address'],
                        'postal_code' => $validated['postal_code'] ?? '',
                    ],
                ],
                'sync_status' => SyncStatus::SYNCED,
            ]
        );

        // Compute totals and create the Order record
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }
        $shippingCost = 25.00;
        $total = $subtotal + $shippingCost;

        $subtotalMinor = (int) ($subtotal * 100);
        $shippingCostMinor = (int) ($shippingCost * 100);
        $totalMinor = (int) ($total * 100);

        $order = Order::create([
            'customer_id' => $customer->id,
            'reference_id' => 'REF-'.strtoupper(Str::random(10)),
            'status' => OrderStatus::Pending,
            'payment_status' => PaymentStatus::Pending,
            'shipping_status' => ShippingStatus::NotShipped,
            'payment_method' => PaymentMethod::CashOnDelivery,
            'currency' => 'SAR',
            'subtotal_minor' => $subtotalMinor,
            'shipping_cost_minor' => $shippingCostMinor,
            'tax_amount_minor' => 0,
            'discount_minor' => 0,
            'total_minor' => $totalMinor,
            'shipping_address' => [
                'name' => $validated['name'],
                'city' => $validated['city'],
                'address' => $validated['address'],
                'postal_code' => $validated['postal_code'] ?? '',
                'phone' => $validated['phone'],
            ],
            'placed_at' => now(),
            'sync_status' => SyncStatus::SYNCED,
        ]);

        // Create OrderItem records
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'name' => $item['name'],
                'quantity' => $item['quantity'],
                'unit_price_minor' => (int) ($item['price'] * 100),
                'total_minor' => (int) (($item['price'] * $item['quantity']) * 100),
            ]);
        }

        // Return a successful redirect to the success page with order details
        return redirect()->route('checkout.success', ['order_id' => $order->id]);
    }

    public function success(Request $request)
    {
        $orderId = $request->query('order_id');
        $order = Order::with('items')->find($orderId);

        if (! $order) {
            return redirect()->route('home');
        }

        return view('customer.checkout-success', ['order' => $order]);
    }
}
