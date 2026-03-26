<?php

return [
    'pages_per_sync' => (int)env('MOVIESYNC_PAGES_PER_SYNC', 1000),
    'genre_cache_ttl' => (int)env('MOVIESYNC_GENRE_CACHE_TTL', 86400), // 24 hours
];
