<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

@php($resolvedFaviconUrl = $faviconUrl ?? '/favicon.ico')

<title>
    {{ filled($metaTitle ?? null) ? $metaTitle : (filled($title ?? null) ? $title.' - '.config('app.name', 'Laravel') : config('app.name', 'Laravel')) }}
</title>

@if (filled($metaDescription ?? null))
    <meta name="description" content="{{ $metaDescription }}">
@endif

<link rel="icon" href="{{ $resolvedFaviconUrl }}" sizes="any">
<link rel="apple-touch-icon" href="{{ $resolvedFaviconUrl }}">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|poppins:500,600,700" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
