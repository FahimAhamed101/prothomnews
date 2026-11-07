<?php

namespace App\Support;

class YouTube
{
    public static function videoId(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        $parts = parse_url($url);
        $host = strtolower($parts['host'] ?? '');

        if (! in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'youtu.be'], true)) {
            return null;
        }

        if ($host === 'youtu.be') {
            $videoId = trim($parts['path'] ?? '', '/');
        } else {
            parse_str($parts['query'] ?? '', $query);
            $videoId = $query['v'] ?? null;

            if (! $videoId && preg_match('~^/(?:embed|shorts)/([^/?]+)~', $parts['path'] ?? '', $matches)) {
                $videoId = $matches[1];
            }
        }

        return is_string($videoId) && preg_match('/^[a-zA-Z0-9_-]{11}$/', $videoId) ? $videoId : null;
    }

    public static function embedUrl(?string $url): ?string
    {
        $videoId = self::videoId($url);

        return $videoId ? "https://www.youtube-nocookie.com/embed/{$videoId}" : null;
    }
}
