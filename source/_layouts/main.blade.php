@php
    $site = $page->content['site'];
    $seo = $site['seo'] ?? [];
    // Open Graph exige URL absoluta: se construye con el baseUrl de config.production.php
    $ogImage = rtrim($page->baseUrl, '/') . ($seo['og_image'] ?? '');
@endphp
<!DOCTYPE html>
<html lang="{{ $page->language ?? 'es' }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="canonical" href="{{ $page->getUrl() }}">
        <meta name="description" content="{{ $page->description }}">
        <meta name="theme-color" content="#f8f4ee">

        <title>{{ $page->title }}</title>

        {{-- Favicons --}}
        <link rel="icon" href="/favicon.ico" sizes="32x32">
        <link rel="icon" href="/assets/images/favicon-32.png" type="image/png" sizes="32x32">
        <link rel="icon" href="/assets/images/favicon-192.png" type="image/png" sizes="192x192">
        <link rel="icon" href="/assets/images/favicon-512.png" type="image/png" sizes="512x512">
        <link rel="apple-touch-icon" href="/assets/images/apple-touch-icon.png">

        {{-- Open Graph / Twitter (se configura en source/_content/site.md) --}}
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="{{ $site['brand']['name'] }}">
        <meta property="og:locale" content="{{ $seo['locale'] ?? 'es_MX' }}">
        <meta property="og:url" content="{{ $page->getUrl() }}">
        <meta property="og:title" content="{{ $page->title }}">
        <meta property="og:description" content="{{ $page->description }}">
        <meta property="og:image" content="{{ $ogImage }}">
        <meta property="og:image:secure_url" content="{{ $ogImage }}">
        <meta property="og:image:type" content="image/jpeg">
        <meta property="og:image:width" content="{{ $seo['og_image_width'] ?? 1200 }}">
        <meta property="og:image:height" content="{{ $seo['og_image_height'] ?? 630 }}">
        <meta property="og:image:alt" content="{{ $seo['og_image_alt'] ?? $page->title }}">

        <meta name="twitter:card" content="{{ $seo['twitter_card'] ?? 'summary_large_image' }}">
        <meta name="twitter:title" content="{{ $page->title }}">
        <meta name="twitter:description" content="{{ $page->description }}">
        <meta name="twitter:image" content="{{ $ogImage }}">
        <meta name="twitter:image:alt" content="{{ $seo['og_image_alt'] ?? $page->title }}">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">


        {{-- Respaldo sin JavaScript: todo el contenido permanece visible --}}
        <noscript>
            <style>
                .reveal { opacity: 1 !important; transform: none !important; }
                [x-cloak] { display: revert !important; }
                .faq-answer { display: block !important; }
            </style>
        </noscript>

        @viteRefresh()
        <link rel="stylesheet" href="{{ vite('source/_assets/css/main.css') }}">
        <script defer type="module" src="{{ vite('source/_assets/js/main.js') }}"></script>
    </head>
    <body class="font-sans antialiased">
        <a href="#inicio" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[60] focus:rounded-full focus:bg-white focus:px-5 focus:py-3 focus:text-sm focus:font-semibold">
            Saltar al contenido
        </a>

        @include('_partials.header')

        <main>
            @yield('body')
        </main>

        @include('_partials.footer')
    </body>
</html>
