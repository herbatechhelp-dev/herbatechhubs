<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="shortcut-shell min-h-screen bg-[radial-gradient(circle_at_top_left,_rgba(56,189,248,0.24),_transparent_32%),radial-gradient(circle_at_top_right,_rgba(251,191,36,0.2),_transparent_28%),linear-gradient(180deg,_#eef6ff_0%,_#d9e7fb_42%,_#eef2ff_100%)] text-slate-900 dark:bg-[radial-gradient(circle_at_top_left,_rgba(56,189,248,0.18),_transparent_24%),radial-gradient(circle_at_top_right,_rgba(249,115,22,0.14),_transparent_24%),linear-gradient(180deg,_#081120_0%,_#111827_52%,_#050b16_100%)] dark:text-slate-100">
        {{ $slot }}

        @fluxScripts
    </body>
</html>