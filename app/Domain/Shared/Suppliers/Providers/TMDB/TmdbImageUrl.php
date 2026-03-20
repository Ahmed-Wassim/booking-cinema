<?php

declare(strict_types=1);

namespace App\Domain\Shared\Suppliers\Providers\TMDB;

final class TmdbImageUrl
{
    private const BASE = 'https://image.tmdb.org/t/p';

    public static function poster(?string $path, string $size = 'w500'): ?string
    {
        return self::build($path, $size);
    }

    public static function backdrop(?string $path, string $size = 'w780'): ?string
    {
        return self::build($path, $size);
    }

    private static function build(?string $path, string $size): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        return self::BASE . '/' . $size . '/' . ltrim($path, '/');
    }
}
