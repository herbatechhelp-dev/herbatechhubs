<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class FaviconFetcher
{
    public function preview(string $url): ?string
    {
        $normalizedUrl = $this->normalizeUrl($url);

        if (! $normalizedUrl) {
            return null;
        }

        return $this->discoverIcon($normalizedUrl);
    }

    public function normalizeUrl(string $url): ?string
    {
        $trimmedUrl = trim($url);

        if ($trimmedUrl === '') {
            return null;
        }

        if (! Str::startsWith($trimmedUrl, ['http://', 'https://'])) {
            $trimmedUrl = 'https://'.$trimmedUrl;
        }

        if (! filter_var($trimmedUrl, FILTER_VALIDATE_URL)) {
            return null;
        }

        return $trimmedUrl;
    }

    public function fallbackIcon(string $url): string
    {
        $host = parse_url($url, PHP_URL_HOST) ?: $url;

        return 'https://www.google.com/s2/favicons?domain='.urlencode((string) $host).'&sz=128';
    }

    private function discoverIcon(string $url): string
    {
        $host = parse_url($url, PHP_URL_HOST);

        if (! $host) {
            return $this->fallbackIcon($url);
        }

        try {
            $response = Http::timeout(4)
                ->retry(1, 150)
                ->accept('text/html,application/xhtml+xml')
                ->get($url);

            if ($response->successful()) {
                $html = $response->body();

                if (preg_match('/<link[^>]+rel=["\'](?:shortcut icon|icon|apple-touch-icon)["\'][^>]+href=["\']([^"\']+)["\']/i', $html, $matches) === 1) {
                    return str_starts_with($matches[1], 'http')
                        ? $matches[1]
                        : rtrim($url, '/').'/'.ltrim($matches[1], '/');
                }
            }
        } catch (\Throwable) {
        }

        return 'https://'.$host.'/favicon.ico';
    }
}