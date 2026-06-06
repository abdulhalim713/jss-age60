<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @php
            $pageProps = $page['props'] ?? [];
            $metaTitle = $pageProps['seo']['title'] ?? ($pageProps['title'] ?? null);
            if ($metaTitle) {
                if (strpos($metaTitle, 'জোড়পুকুরিয়া') === false && strpos($metaTitle, 'হীরক') === false) {
                    $metaTitle = $metaTitle . ' | জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়';
                }
            } else {
                $metaTitle = 'হীরক জয়ন্তী | জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়';
            }
            $metaDescription = $pageProps['seo']['description'] ?? ($pageProps['description'] ?? 'জোড়পুকুরিয়া মাধ্যমিক বিদ্যালয়ের হীরক জয়ন্তী ২০২৭ উদযাপন উপলক্ষে প্রাক্তন শিক্ষার্থীদের পুনর্মিলনী ও মিলনমেলা।');
            $metaImage = $pageProps['seo']['image'] ?? asset('images/hirak-jayanti-logo.png');
            $currentUrl = url()->current();
        @endphp

        <title inertia>{{ $metaTitle }}</title>

        <!-- Favicon -->
        <link rel="shortcut icon" href="{{ asset('images/hirak-jayanti-logo.png') }}" type="image/x-icon">
        <link rel="icon" href="{{ asset('images/hirak-jayanti-logo.png') }}" type="image/png">

        <!-- SEO Meta Tags -->
        <meta name="description" content="{{ $metaDescription }}">

        <!-- Facebook Meta Tags (Open Graph) -->
        <meta property="og:url" content="{{ $currentUrl }}">
        <meta property="og:type" content="website">
        <meta property="og:title" content="{{ $metaTitle }}">
        <meta property="og:description" content="{{ $metaDescription }}">
        <meta property="og:image" content="{{ $metaImage }}">

        <!-- Twitter Meta Tags -->
        <meta name="twitter:card" content="summary_large_image">
        <meta property="twitter:domain" content="{{ request()->getHost() }}">
        <meta property="twitter:url" content="{{ $currentUrl }}">
        <meta name="twitter:title" content="{{ $metaTitle }}">
        <meta name="twitter:description" content="{{ $metaDescription }}">
        <meta name="twitter:image" content="{{ $metaImage }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
