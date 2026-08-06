@props(["product"])

@php
    $price       = $product->price ?? null;
    $salePrice   = $product->sale_price ?? null;
    $hasDiscount = $salePrice !== null && $salePrice < $price;
    $image       = $product->thumbnail_url ?? asset("images/placeholder-product.svg");
    $isNew       = $product->created_at?->gt(now()->subDays(7)) ?? false;
@endphp

<article class="card group relative" x-data="{ liked: false }">
    {{-- Badges --}}
    <div class="absolute top-3 right-3 z-10 flex flex-col gap-1.5">
        @if($isNew)
            <span class="badge-success">جديد</span>
        @endif
        @if($hasDiscount)
            @php $discountPct = (int) round((($price - $salePrice) / $price) * 100); @endphp
            <span class="badge-danger">-{{ $discountPct }}%</span>
        @endif
    </div>

    {{-- Wishlist toggle --}}
    <button @click.prevent="liked = !liked"
            class="absolute top-3 left-3 z-10 w-9 h-9 rounded-full bg-white/90 hover:bg-white shadow-soft flex items-center justify-center transition"
            aria-label="إضافة للمفضلة">
        <svg :class="liked ? 'text-red-500 fill-red-500' : 'text-gray-500'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
        </svg>
    </button>

    {{-- Image --}}
    <a href="{{ route('shop.show', $product->slug ?? $product->id) }}" class="block aspect-square overflow-hidden bg-gray-50">
        <img src="{{ $image }}"
             alt="{{ $product->name }}"
             loading="lazy"
             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
    </a>

    {{-- Body --}}
    <div class="p-4">
        @if($product->category?->name ?? false)
            <p class="text-xs text-gray-500 mb-1">{{ $product->category->name }}</p>
        @endif

        <h3 class="font-semibold text-gray-900 text-sm line-clamp-2 mb-2 min-h-[2.5rem]">
            <a href="{{ route('shop.show', $product->slug ?? $product->id) }}" class="hover:text-brand-600 transition">
                {{ $product->name }}
            </a>
        </h3>

        <div class="flex items-center justify-between mt-3">
            <div class="flex items-baseline gap-2">
                @if($price !== null)
                    <span class="text-lg font-bold text-brand-700">{{ number_format($salePrice ?? $price, 2) }} ر.س</span>
                    @if($hasDiscount)
                        <span class="text-xs text-gray-400 line-through">{{ number_format($price, 2) }}</span>
                    @endif
                @else
                    <span class="text-sm text-gray-500">—</span>
                @endif
            </div>

            <button @click.prevent="$store.cart.add({
                        id: {{ $product->id }},
                        name: '{{ addslashes($product->name) }}',
                        price: {{ $product->sale_price ?? $product->price ?? 0 }},
                        thumbnail_url: '{{ $image }}'
                    })"
                    class="w-9 h-9 rounded-xl bg-brand-50 hover:bg-brand-600 hover:text-white text-brand-600 flex items-center justify-center transition"
                    aria-label="أضف للسلة">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </button>
        </div>
    </div>
</article>
