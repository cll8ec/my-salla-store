@extends("layouts.app")

@section("title", "تم تأكيد طلبك — رافال")

@section("content")
<div class="container-rtl py-12 max-w-3xl mx-auto px-4 text-center" x-data="{
    init() {
        // Clear the client-side cart upon successful checkout!
        $store.cart.items = [];
    }
}">
    {{-- Success Checkmark Icon with elegant gold gradient --}}
    <div class="inline-flex items-center justify-center w-24 h-20 bg-gradient-to-br from-brand-400 to-brand-600 rounded-full text-white text-4xl shadow-lg mb-6 animate-bounce">
        ✓
    </div>

    <h1 class="text-3xl font-extrabold text-gray-900 mb-2">تم تأكيد طلبك بنجاح! 🎉</h1>
    <p class="text-gray-500 mb-8">شكراً لك لتسوقك من متجر رافال. لقد تم تسجيل طلبك بنجاح وسيتواصل معك فريق الشحن قريباً.</p>

    {{-- Order Summary Card --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 text-right space-y-6 mb-8">
        <div class="flex items-center justify-between border-b pb-4">
            <div>
                <span class="text-xs text-gray-400 block">رقم المرجع للطلب</span>
                <span class="font-bold text-gray-950 text-base" x-text="'{{ $order->reference_id }}'"></span>
            </div>
            <div>
                <span class="text-xs text-gray-400 block">تاريخ الطلب</span>
                <span class="text-gray-700 text-sm" x-text="'{{ $order->created_at->format('Y-m-d H:i') }}'"></span>
            </div>
        </div>

        {{-- Ordered Items --}}
        <div>
            <h3 class="font-bold text-gray-950 text-sm mb-4">المنتجات المطلوبة</h3>
            <div class="space-y-4">
                @foreach($order->items as $item)
                    <div class="flex items-center gap-4 text-sm">
                        <img src="{{ $item->product->thumbnail_url ?? 'https://picsum.photos/seed/' . $item->product_id . '/80/80' }}" class="w-12 h-12 rounded-xl object-cover border border-gray-100">
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-gray-900 truncate">{{ $item->name }}</p>
                            <p class="text-xs text-gray-500">{{ $item->quantity }} × {{ number_format($item->unit_price_minor / 100, 2) }} ر.س</p>
                        </div>
                        <p class="font-bold text-gray-900 whitespace-nowrap">{{ number_format($item->total_minor / 100, 2) }} ر.س</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Shipping Details --}}
        <div class="border-t pt-4">
            <h3 class="font-bold text-gray-950 text-sm mb-2">عنوان التوصيل</h3>
            <p class="text-sm text-gray-700">{{ $order->shipping_address['name'] }}</p>
            <p class="text-sm text-gray-500 mt-0.5">{{ $order->shipping_address['city'] }} · {{ $order->shipping_address['address'] }}</p>
            <p class="text-sm text-gray-500 mt-0.5" dir="ltr">{{ $order->shipping_address['phone'] }}</p>
        </div>

        {{-- Totals --}}
        <div class="border-t pt-4 space-y-2">
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">المجموع الفرعي:</span>
                <span class="font-bold text-gray-950">{{ number_format($order->subtotal_minor / 100, 2) }} ر.س</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">الشحن والتوصيل:</span>
                <span class="font-bold text-gray-950">{{ number_format($order->shipping_cost_minor / 100, 2) }} ر.s</span>
            </div>
            <div class="flex justify-between text-base font-extrabold border-t pt-3 mt-3">
                <span>الإجمالي:</span>
                <span class="text-brand-700">{{ number_format($order->total_minor / 100, 2) }} ر.س</span>
            </div>
        </div>
    </div>

    {{-- Call to Actions --}}
    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
        <a href="{{ url('/') }}" class="btn-primary px-8 py-3 w-full sm:w-auto">العودة للرئيسية 🏠</a>
        <a href="{{ route('shop.index') }}" class="btn-outline px-8 py-3 w-full sm:w-auto bg-white">الاستمرار بالتسوق 🛍️</a>
    </div>
</div>
@endsection
