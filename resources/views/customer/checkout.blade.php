@extends("layouts.app")

@section("title", "إتمام الطلب — رافال")

@section("content")
<div class="container-rtl py-10" x-data="{ shipping: 25.00 }">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">إتمام الطلب 🛒</h1>

    @guest
        <div class="bg-blue-50 border-r-4 border-blue-500 text-blue-700 p-4 rounded-xl mb-6 text-sm" role="alert">
            لديك حساب بالفعل؟ <a href="{{ route('login') }}" class="font-semibold underline">سجّل دخولك</a> لتسريع عملية الشراء.
        </div>
    @endguest

    <form action="{{ route('checkout.place') }}" method="POST" class="grid lg:grid-cols-3 gap-8">
        @csrf

        {{-- Hidden input containing the dynamic cart items from localStorage --}}
        <input type="hidden" name="cart_items" :value="JSON.stringify($store.cart.items)">

        <div class="lg:col-span-2 space-y-6">
            {{-- Contact --}}
            <section class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4">معلومات التواصل</h2>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">الاسم الكامل *</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">رقم الجوال *</label>
                        <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone ?? '') }}" required dir="ltr" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-500 text-sm">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">البريد الإلكتروني *</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-500 text-sm">
                    </div>
                </div>
            </section>

            {{-- Shipping address --}}
            <section class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4">عنوان الشحن</h2>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">المدينة *</label>
                        <select name="city" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-500 text-sm bg-white">
                            <option value="">اختر المدينة</option>
                            @foreach(["الرياض","جدة","مكة","المدينة","الدمام","الخبر","الطائف","تبوك","أبها","حائل"] as $city)
                                <option value="{{ $city }}" {{ old('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">العنوان التفصيلي *</label>
                        <textarea name="address" rows="3" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-500 text-sm">{{ old('address') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">الرمز البريدي</label>
                        <input type="text" name="postal_code" value="{{ old('postal_code') }}" dir="ltr" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-500 text-sm">
                    </div>
                </div>
            </section>

            {{-- Payment --}}
            <section class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <h2 class="text-lg font-bold text-gray-900 mb-4">طريقة الدفع</h2>
                <p class="text-sm text-gray-500 mb-4">سيتم توجيهك إلى صفحة الدفع الآمنة بعد تأكيد الطلب.</p>

                <label class="flex items-start gap-3 p-4 rounded-xl border-2 border-brand-500 bg-brand-50 cursor-pointer">
                    <input type="radio" name="payment_method" value="salla" checked class="mt-0.5 text-brand-600 focus:ring-brand-500">
                    <div>
                        <p class="font-semibold text-gray-900">الدفع الإلكتروني</p>
                        <p class="text-sm text-gray-600 mt-0.5">بطاقة ائتمان · مدى · Apple Pay · تحويل بنكي</p>
                    </div>
                </label>
            </section>
        </div>

        {{-- Summary --}}
        <aside class="lg:col-span-1">
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm sticky top-24">
                <h3 class="font-bold text-gray-900 mb-4 text-base">ملخص الطلب</h3>

                {{-- Dynamic Cart Items via Alpine --}}
                <div class="space-y-3 mb-4 max-h-64 overflow-y-auto scrollbar-thin">
                    <template x-for="item in $store.cart.items" :key="item.id">
                        <div class="flex items-center gap-3 text-sm">
                            <img :src="item.thumbnail_url" class="w-12 h-12 rounded-lg object-cover">
                            <div class="flex-1 min-w-0">
                                <p class="font-medium truncate" x-text="item.name"></p>
                                <p class="text-xs text-gray-500" x-text="item.quantity + ' × ' + parseFloat(item.price).toFixed(2) + ' ر.س'"></p>
                            </div>
                            <p class="font-semibold whitespace-nowrap" x-text="(item.price * item.quantity).toFixed(2) + ' ر.س'"></p>
                        </div>
                    </template>
                    <template x-if="$store.cart.items.length === 0">
                        <p class="text-sm text-gray-500 text-center py-4">السلة فارغة</p>
                    </template>
                </div>

                <dl class="space-y-2 text-sm border-t pt-4">
                    <div class="flex justify-between">
                        <dt class="text-gray-600">المجموع الفرعي</dt>
                        <dd x-text="$store.cart.total.toFixed(2) + ' ر.س'"></dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-600">الشحن</dt>
                        <dd x-text="shipping.toFixed(2) + ' ر.س'"></dd>
                    </div>
                    <div class="flex justify-between text-base font-bold border-t pt-3 mt-3">
                        <dt>الإجمالي</dt>
                        <dd class="text-brand-700" x-text="($store.cart.total + shipping).toFixed(2) + ' ر.س'"></dd>
                    </div>
                </dl>

                <button type="submit" :disabled="$store.cart.items.length === 0" class="btn-primary w-full py-3 mt-6 disabled:opacity-50 disabled:cursor-not-allowed">تأكيد الطلب</button>

                <p class="text-xs text-gray-400 text-center mt-4">بالنقر على "تأكيد الطلب" فأنت توافق على شروط الاستخدام.</p>
            </div>
        </aside>
    </form>
</div>
@endsection
