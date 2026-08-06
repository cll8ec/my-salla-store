@extends('layouts.app')
@section('title', 'المتجر — رافال')
@section('content')
<div class="max-w-7xl mx-auto px-4 py-8" x-data="{
    searchQuery: (new URLSearchParams(window.location.search)).get('q') || '',
    selectedCategory: '',
    minPrice: 0,
    maxPrice: 1000,
    sortOrder: 'name_asc',
    products: @json($products),
    categories: @json($categories),
    get filteredProducts() {
        return this.products.filter(p => {
            const matchesQuery = !this.searchQuery ||
                                 p.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                                 p.category.name.toLowerCase().includes(this.searchQuery.toLowerCase());
            const matchesCategory = !this.selectedCategory || p.category.name === this.selectedCategory;
            const price = p.sale_price || p.price;
            const matchesPrice = price >= this.minPrice && price <= this.maxPrice;
            return matchesQuery && matchesCategory && matchesPrice;
        }).sort((a, b) => {
            const priceA = a.sale_price || a.price;
            const priceB = b.sale_price || b.price;
            if (this.sortOrder === 'price_asc') return priceA - priceB;
            if (this.sortOrder === 'price_desc') return priceB - priceA;
            if (this.sortOrder === 'name_asc') return a.name.localeCompare(b.name, 'ar');
            if (this.sortOrder === 'name_desc') return b.name.localeCompare(a.name, 'ar');
            return 0;
        });
    }
}">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-gray-200 pb-5 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">جميع المنتجات 💎</h1>
            <p class="text-sm text-gray-500 mt-1">تصفح تشكيلتنا الحصرية الفاخرة من الذهب والمجوهرات</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-sm text-gray-500">ترتيب حسب:</span>
            <select x-model="sortOrder" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 bg-white">
                <option value="name_asc">الاسم (أ - ي)</option>
                <option value="name_desc">الاسم (ي - أ)</option>
                <option value="price_asc">السعر (من الأقل للأعلى)</option>
                <option value="price_desc">السعر (من الأعلى للأقل)</option>
            </select>
        </div>
    </div>

    <div class="grid lg:grid-cols-4 gap-8">
        {{-- Filters Sidebar --}}
        <aside class="space-y-6">
            {{-- Search Bar --}}
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                <h3 class="font-semibold text-gray-900 mb-3 text-sm">بحث سريع</h3>
                <div class="relative">
                    <input type="text" x-model="searchQuery" placeholder="ابحث باسم المنتج أو الصنف..." class="w-full pl-4 pr-10 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-500 text-sm">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                        </svg>
                    </span>
                </div>
            </div>

            {{-- Categories --}}
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                <h3 class="font-semibold text-gray-900 mb-3 text-sm">التصنيفات</h3>
                <div class="flex flex-col gap-2">
                    <button @click="selectedCategory = ''"
                            :class="selectedCategory === '' ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-gray-600 hover:bg-gray-50'"
                            class="w-full text-right px-3 py-2 rounded-xl transition-all text-sm">
                        جميع الأقسام
                    </button>
                    <template x-for="cat in categories" :key="cat">
                        <button @click="selectedCategory = cat"
                                :class="selectedCategory === cat ? 'bg-brand-50 text-brand-700 font-semibold' : 'text-gray-600 hover:bg-gray-50'"
                                class="w-full text-right px-3 py-2 rounded-xl transition-all text-sm"
                                x-text="cat">
                        </button>
                    </template>
                </div>
            </div>

            {{-- Price Range --}}
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                <h3 class="font-semibold text-gray-900 mb-3 text-sm">نطاق السعر</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <span class="text-xs text-gray-400 block">الحد الأدنى</span>
                            <div class="flex items-center gap-1 border border-gray-200 rounded-xl px-2 py-1.5 mt-1">
                                <input type="number" x-model.number="minPrice" class="w-16 border-0 p-0 text-sm focus:ring-0 focus:outline-none">
                                <span class="text-xs text-gray-500">ر.س</span>
                            </div>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 block">الحد الأقصى</span>
                            <div class="flex items-center gap-1 border border-gray-200 rounded-xl px-2 py-1.5 mt-1">
                                <input type="number" x-model.number="maxPrice" class="w-16 border-0 p-0 text-sm focus:ring-0 focus:outline-none">
                                <span class="text-xs text-gray-500">ر.س</span>
                            </div>
                        </div>
                    </div>
                    <input type="range" min="0" max="1000" x-model.number="maxPrice" class="w-full h-1 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-brand-600">
                </div>
            </div>
        </aside>

        {{-- Products Grid --}}
        <main class="lg:col-span-3">
            <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-6">
                <template x-for="product in filteredProducts" :key="product.id">
                    <article class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden group hover:shadow-md transition-all relative flex flex-col justify-between">
                        {{-- Image and Badges --}}
                        <div class="relative aspect-square overflow-hidden bg-gray-50">
                            <div class="absolute top-3 right-3 z-10 flex flex-col gap-1.5">
                                <template x-if="product.sale_price">
                                    <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded-lg">تخفيض</span>
                                </template>
                            </div>
                            <a :href="'/shop/' + product.id" class="block w-full h-full">
                                <img :src="product.thumbnail_url" :alt="product.name" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            </a>
                        </div>

                        {{-- Details --}}
                        <div class="p-4 flex-1 flex flex-col justify-between">
                            <div>
                                <p class="text-[10px] text-brand-600 font-semibold mb-1" x-text="product.category.name"></p>
                                <h3 class="font-bold text-gray-900 text-sm mb-2 min-h-[2.5rem]">
                                    <a :href="'/shop/' + product.id" class="hover:text-brand-600 transition" x-text="product.name"></a>
                                </h3>
                            </div>

                            <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-50">
                                <div class="flex flex-col">
                                    <span class="text-sm font-extrabold text-brand-700" x-text="(product.sale_price || product.price) + ' ر.س'"></span>
                                    <template x-if="product.sale_price">
                                        <span class="text-xs text-gray-400 line-through" x-text="product.price + ' ر.س'"></span>
                                    </template>
                                </div>
                                <button @click.prevent="$store.cart.add(product)" class="w-10 h-10 rounded-xl bg-brand-50 hover:bg-brand-600 hover:text-white text-brand-600 flex items-center justify-center transition-all shadow-sm hover:shadow" aria-label="أضف للسلة">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </article>
                </template>
            </div>

            {{-- Empty State --}}
            <template x-if="filteredProducts.length === 0">
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-20 h-20 bg-brand-50 rounded-full flex items-center justify-center text-brand-600 text-3xl mb-4">🔍</div>
                    <h3 class="text-lg font-bold text-gray-900">عذراً، لم نجد أي نتائج!</h3>
                    <p class="text-gray-500 text-sm mt-1">حاول البحث بكلمات أخرى أو تغيير خيارات التصفية.</p>
                </div>
            </template>
        </main>
    </div>
</div>
@endsection
