<?php

declare(strict_types=1);

use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;

$mockProducts = function (int $count): array {
    $names = ['سلسلة ذهبية', 'ساعة فاخرة', 'بوكس هدايا', 'سبحة عقيق', 'ميدالية فضية', 'قلم راقي', 'سوار لؤلؤ', 'لوحة إسلامية'];
    $cats = ['السلاسل', 'الساعات', 'الهدايا', 'العقيق', 'الميداليات', 'الأقلام', 'الإكسسوارات', 'الديكور'];
    $out = [];
    for ($i = 0; $i < $count; $i++) {
        $out[] = (object) [
            'id' => $i + 1,
            'name' => $names[$i % count($names)].' '.($i + 1),
            'slug' => 'product-'.($i + 1),
            'price' => rand(100, 800),
            'sale_price' => $i % 3 === 0 ? rand(50, 400) : null,
            'thumbnail_url' => "https://picsum.photos/seed/rafal{$i}/400/400",
            'category' => (object) ['name' => $cats[$i % count($cats)]],
            'stock' => rand(5, 50),
            'created_at' => now()->subDays(rand(0, 30)),
        ];
    }

    return $out;
};

$productView = function (int $id) {
    return view('customer.product', [
        'product' => (object) [
            'id' => $id, 'name' => 'سلسلة ذهبية فاخرة '.$id, 'slug' => 'demo-'.$id,
            'price' => 450.00, 'sale_price' => 349.00,
            'thumbnail_url' => "https://picsum.photos/seed/{$id}/800/800",
            'description' => '<p>سلسلة ذهبية فاخرة مصنوعة من أجود أنواع الذهب عيار 21.</p>',
            'category' => (object) ['id' => 1, 'name' => 'السلاسل'],
            'stock' => 25, 'reviews_avg_rating' => 4.7, 'reviews_count' => 23,
        ],
        'reviews' => collect(),
    ]);
};

// ─── Home & Static ──────────────────────────────────────
Route::get('/', fn () => view('customer.home', ['featured' => $mockProducts(8)]))->name('home');
Route::view('/about', 'customer.home')->name('about');
Route::view('/contact', 'customer.home')->name('contact');
Route::view('/categories', 'customer.home')->name('categories.index');
Route::view('/faq', 'customer.home')->name('faq');
Route::view('/shipping', 'customer.home')->name('shipping');
Route::view('/returns', 'customer.home')->name('returns');
Route::view('/privacy', 'customer.home')->name('privacy');
Route::view('/terms', 'customer.home')->name('terms');
Route::view('/track-order', 'customer.home')->name('track-order');

// ─── Shop ───────────────────────────────────────────────
Route::get('/shop', function () use ($mockProducts) {
    $products = $mockProducts(24); // 24 products for rich testing
    $categories = collect($products)->map(fn ($p) => $p->category->name)->unique()->values();

    return view('customer.shop', [
        'products' => collect($products),
        'categories' => $categories,
    ]);
})->name('shop.index');

Route::get('/shop/{id}', $productView)->where('id', '[0-9]+')->name('shop.show');

// ─── Cart / Wishlist / Checkout ─────────────────────────
Route::view('/cart', 'customer.cart')->name('cart.index');
Route::view('/wishlist', 'customer.wishlist')->name('wishlist.index');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'place'])->name('checkout.place');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::post('/cart/add/{id}', fn () => back())->where('id', '[0-9]+')->name('cart.add');
Route::patch('/cart/{id}', fn () => back())->where('id', '[0-9]+')->name('cart.update');
Route::delete('/cart/{id}', fn () => back())->where('id', '[0-9]+')->name('cart.remove');
Route::post('/wishlist/toggle/{id}', fn () => back())->where('id', '[0-9]+')->name('wishlist.toggle');

// ─── Orders ─────────────────────────────────────────────
Route::view('/orders', 'customer.orders')->name('orders.index');
Route::get('/orders/{id}', function (int $id) {
    return view('customer.order-detail', ['order' => (object) [
        'id' => $id, 'number' => '100'.$id, 'total' => 374.00,
        'subtotal' => 349.00, 'shipping' => 25.00, 'discount' => 0,
        'status' => (object) ['value' => 'pending', 'label' => fn () => 'قيد المراجعة', 'color' => fn () => 'warning'],
        'created_at' => now(), 'items' => collect(),
        'shipping_name' => 'محمد', 'shipping_phone' => '+966500000000',
        'shipping_address' => 'حي الياسمين', 'shipping_city' => 'الرياض', 'shipping_postal_code' => '12345',
    ]]);
})->where('id', '[0-9]+')->name('orders.show');
Route::patch('/orders/{id}/cancel', fn () => back())->where('id', '[0-9]+')->name('orders.cancel');

// ─── Account ────────────────────────────────────────────
Route::prefix('account')->name('account.')->group(function () {
    Route::view('/profile', 'customer.account.profile')->name('profile');
    Route::put('/profile', fn () => back())->name('profile.update');
    Route::view('/addresses', 'customer.account.addresses')->name('addresses');
    Route::delete('/addresses/{id}', fn () => back())->where('id', '[0-9]+')->name('addresses.destroy');
    Route::view('/password', 'customer.account.change-password')->name('password');
    Route::put('/password', fn () => back())->name('password.update');
});

// ─── Auth ───────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::post('/login', fn () => back())->name('login.attempt');
    Route::view('/register', 'auth.register')->name('register');
    Route::post('/register', fn () => back())->name('register.attempt');
    Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');
    Route::post('/forgot-password', fn () => back())->name('password.email');
    Route::post('/auth/salla', fn () => back())->name('auth.salla');
});
Route::post('/logout', fn () => redirect('/'))->name('logout');

// ─── Admin ──────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    Route::view('/', 'admin.dashboard')->name('dashboard');
    Route::view('/settings', 'admin.settings')->name('settings');
    Route::put('/settings', fn () => back())->name('settings.update');
    Route::put('/settings/salla', fn () => back())->name('settings.salla.update');
    Route::put('/settings/shipping', fn () => back())->name('settings.shipping.update');
    Route::view('/orders', 'admin.orders.index')->name('orders.index');
    Route::get('/orders/{id}', fn (int $id) => view('admin.dashboard'))->where('id', '[0-9]+')->name('orders.show');
    Route::view('/customers', 'admin.customers.index')->name('customers.index');
    Route::prefix('products')->name('products.')->group(function () {
        Route::view('/', 'admin.products.index')->name('index');
        Route::view('/create', 'admin.dashboard')->name('create');
        Route::get('/{id}', function (int $id) {
            return view('admin.products.show', ['product' => (object) [
                'id' => $id, 'name' => 'منتج تجريبي', 'sku' => 'SKU-'.$id,
                'price' => 450, 'sale_price' => 349, 'stock' => 25, 'weight' => 0.5,
                'thumbnail_url' => "https://picsum.photos/seed/{$id}/800/600",
                'description' => '<p>وصف تجريبي للمنتج.</p>',
                'category' => (object) ['name' => 'عام'],
                'status' => (object) ['value' => 'published', 'label' => fn () => 'منشور'],
                'created_at' => now(),
            ]]);
        })->where('id', '[0-9]+')->name('show');
        Route::get('/{id}/edit', fn () => view('admin.dashboard'))->where('id', '[0-9]+')->name('edit');
        Route::delete('/{id}', fn () => back())->where('id', '[0-9]+')->name('destroy');
    });
    Route::prefix('sync')->name('sync.')->group(function () {
        Route::view('/', 'admin.dashboard')->name('index');
        Route::post('/run', fn () => back())->name('run');
    });
});

require __DIR__ . '/settings.php';
