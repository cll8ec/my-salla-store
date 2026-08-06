<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'رافال — متجر حلي فاخر')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&family=Readex+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#fef5f0', 100: '#fce8de', 200: '#f8d5c3', 300: '#f4b8a0',
                            400: '#ef8c67', 500: '#e85d2f', 600: '#d84a1b', 700: '#b83816',
                            800: '#943016', 900: '#7a2815'
                        },
                        canvasMist: 'var(--color-canvas-mist)',
                        pureWhite: 'var(--color-pure-white)',
                        inkBlack: 'var(--color-ink-black)',
                        faintBorder: 'var(--color-faint-border)',
                        mutedGray: 'var(--color-muted-gray)',
                        coolStone: 'var(--color-cool-stone)',
                        warmFog: 'var(--color-warm-fog)',
                        shopViolet: 'var(--color-shop-violet)',
                        violetWash: 'var(--color-violet-wash)',
                        slateInk: 'var(--color-slate-ink)',
                        ashVeil: 'var(--color-ash-veil)'
                    },
                    fontFamily: { cairo: ['Cairo', 'sans-serif'], readex: ['Readex Pro', 'sans-serif'] }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/persist@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            /* Colors */
            --color-canvas-mist: #f2f4f5;
            --color-pure-white: #ffffff;
            --color-ink-black: #000000;
            --color-faint-border: #ebebeb;
            --color-muted-gray: #787574;
            --color-cool-stone: #cccccc;
            --color-warm-fog: #acb0aa;
            --color-shop-violet: #5433eb;
            --color-violet-wash: #c0b5f3;
            --color-slate-ink: #332f2d;
            --color-ash-veil: #665a54;

            /* Typography */
            --font-gt-standard: 'GT Standard', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --font-shopify-sans: 'Shopify Sans', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;

            /* Typography — Scale */
            --text-caption: 11px;
            --leading-caption: 1.33;
            --text-body-sm: 12px;
            --leading-body-sm: 1.33;
            --text-body: 14px;
            --leading-body: 1.33;
            --text-body-lg: 16px;
            --leading-body-lg: 1.33;

            /* Spacing */
            --spacing-4: 4px;
            --spacing-6: 6px;
            --spacing-8: 8px;
            --spacing-10: 10px;
            --spacing-11: 11px;
            --spacing-12: 12px;
            --spacing-16: 16px;
            --spacing-20: 20px;
            --spacing-24: 24px;
            --spacing-32: 32px;
            --spacing-38: 38px;
            --spacing-40: 40px;
            --spacing-48: 48px;
            --spacing-64: 64px;

            /* Border Radius */
            --radius-md: 4px;
            --radius-lg: 8px;
            --radius-xl: 11.4046px;
            --radius-2xl: 17.1064px;
            --radius-2xl-2: 20px;
            --radius-2xl-3: 22.8092px;
            --radius-3xl: 28px;
            --radius-3xl-2: 32px;
            --radius-full: 9999px;

            /* Shadows */
            --shadow-sm: rgba(0, 0, 0, 0.06) 0px 2px 8px 0px;
            --shadow-sm-2: rgba(0, 0, 0, 0.1) 0px 4px 6px -1px, rgba(0, 0, 0, 0.1) 0px 2px 4px -2px;
            --shadow-lg: rgba(0, 0, 0, 0.12) 0px 4px 24px 0px;
            --shadow-lg-2: rgba(69, 36, 219, 0.34) 0px 4px 24px 0px;
        }

        * { font-family: 'Cairo', 'Readex Pro', sans-serif; }
        html { scroll-behavior: smooth; }
        body { background-color: var(--color-canvas-mist); color: var(--color-slate-ink); }
    </style>
    @stack('head')
</head>
<body class="bg-gray-50">
    @include('components.navbar')
    <main class="flex-1">
        @yield('content')
    </main>
    @include('components.cart-drawer')
    @include('components.toast')
    
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('cart', {
                items: Alpine.$persist([]).as('cart_items'),
                isOpen: false,
                add(product, quantity = 1) {
                    const existing = this.items.find(item => item.id === product.id);
                    if (existing) { existing.quantity += quantity; } 
                    else { this.items.push({ ...product, quantity }); }
                    this.notify('تمت الإضافة بنجاح ✓', 'success');
                },
                remove(productId) { this.items = this.items.filter(item => item.id !== productId); },
                updateQuantity(productId, quantity) {
                    if (quantity <= 0) { this.remove(productId); } 
                    else {
                        const item = this.items.find(item => item.id === productId);
                        if (item) item.quantity = quantity;
                    }
                },
                get total() { return this.items.reduce((sum, item) => sum + (item.price * item.quantity), 0); },
                get itemCount() { return this.items.reduce((sum, item) => sum + item.quantity, 0); },
                notify(message, type = 'info') { Alpine.store('toast').show(message, type); }
            });
            Alpine.store('toast', {
                messages: [],
                show(message, type = 'info') {
                    const id = Date.now();
                    this.messages.push({ id, message, type });
                    setTimeout(() => { this.messages = this.messages.filter(m => m.id !== id); }, 3000);
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
