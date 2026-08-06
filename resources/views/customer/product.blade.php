@extends("layouts.app")
@section("title", $product->name . " — رافال")
@section("content")
<div class="container-rtl py-10">
    <nav class="text-sm text-gray-500 mb-6 flex items-center gap-2">
        <a href="/" class="hover:text-brand-600">الرئيسية</a>
        <span>›</span>
        <a href="/shop" class="hover:text-brand-600">المتجر</a>
        <span>›</span>
        <span class="text-gray-700">{{ $product->name }}</span>
    </nav>

    <div x-data="{ qty: 1 }" class="grid lg:grid-cols-2 gap-10">
        <div>
            <div class="card aspect-square overflow-hidden bg-gray-50 mb-4">
                <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            </div>
        </div>

        <div>
            @if($product->category ?? false)
                <a href="/shop" class="text-sm text-brand-600 hover:text-brand-700 font-medium">
                    {{ $product->category->name }}
                </a>
            @endif

            <h1 class="text-3xl font-bold text-gray-900 mt-2 mb-4">{{ $product->name }}</h1>

            <div class="flex items-center gap-2 mb-4">
                <div class="flex items-center">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="w-4 h-4 {{ $i <= 4 ? 'text-amber-400 fill-amber-400' : 'text-gray-300' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.957a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.367 2.447a1 1 0 00-.364 1.118l1.287 3.957c.3.922-.755 1.688-1.539 1.118l-3.366-2.447a1 1 0 00-1.176 0l-3.366 2.447c-.783.57-1.838-.196-1.539-1.118l1.287-3.957a1 1 0 00-.364-1.118L2.063 9.384c-.784-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.957z"/></svg>
                    @endfor
                </div>
                <span class="text-sm text-gray-600">({{ $product->reviews_count ?? 23 }} تقييم)</span>
            </div>

            <div class="flex items-baseline gap-3 mb-6">
                <span class="text-4xl font-bold text-brand-700">{{ number_format($product->sale_price ?? $product->price, 2) }} <span class="text-lg">ر.س</span></span>
                @if($product->sale_price ?? false)
                    <span class="text-lg text-gray-400 line-through">{{ number_format($product->price, 2) }}</span>
                @endif
            </div>

            <div class="prose prose-sm text-gray-600 mb-6">
                {!! $product->description ?? '<p>منتج فاخر من تشكيلة رافال المميزة.</p>' !!}
            </div>

            <div class="space-y-3">
                <div class="flex items-stretch gap-3">
                    <div class="flex items-center border border-gray-200 rounded-xl">
                        <button type="button" @click="qty = Math.max(1, qty - 1)" class="w-10 h-12 hover:bg-gray-50">−</button>
                        <input type="number" name="quantity" x-model.number="qty" min="1" max="{{ $product->stock ?? 99 }}" class="w-16 text-center border-0 focus:ring-0">
                        <button type="button" @click="qty = Math.min({{ $product->stock ?? 99 }}, qty + 1)" class="w-10 h-12 hover:bg-gray-50">+</button>
                    </div>
                    <button type="button" @click="$store.cart.add({
                        id: {{ $product->id }},
                        name: '{{ addslashes($product->name) }}',
                        price: {{ $product->sale_price ?? $product->price }},
                        thumbnail_url: '{{ $product->thumbnail_url }}'
                    }, qty)" class="btn-primary flex-1 py-3">🛒 أضف إلى السلة</button>
                </div>
            </div>

            <div class="mt-8 grid grid-cols-2 gap-3 text-sm">
                <div class="flex items-center gap-2 text-gray-600"><span>🚚</span> توصيل سريع</div>
                <div class="flex items-center gap-2 text-gray-600"><span>🔄</span> إرجاع مجاني 14 يوم</div>
                <div class="flex items-center gap-2 text-gray-600"><span>✅</span> منتج أصلي 100%</div>
                <div class="flex items-center gap-2 text-gray-600"><span>💳</span> دفع آمن</div>
            </div>
        </div>
    </div>
</div>
@endsection
