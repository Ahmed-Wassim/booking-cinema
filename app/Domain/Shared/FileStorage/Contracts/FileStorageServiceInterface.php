<?php

declare(strict_types=1);

namespace App\Domain\Shared\FileStorage\Contracts;

use Illuminate\Http\UploadedFile;

interface FileStorageServiceInterface
{
    /**
     * Download file from URL and store under the given path (relative to disk).
     * Returns the stored path on success, null on failure.
     */
    public function storeFromUrl(string $url, string $path): ?string;

    /**
     * Download from URL and store in directory with optional filename.
     * If filename is null, derive from URL or use a generated name.
     * Returns the stored path (e.g. "movies/posters/123.jpg") or null.
     */
    public function downloadAndStore(string $url, string $directory, ?string $filename = null): ?string;

    /**
     * Store an uploaded file (from API multipart/form-data or Blade form).
     * Use in controllers, API handlers, or any service that receives UploadedFile.
     *
     * @param  string  $directory  Path under disk (e.g. "photos", "avatars")
     * @param  string|null  $filename  Custom filename; if null, uses original name (sanitized) or unique id
     * @return string|null  Stored path (e.g. "photos/abc.jpg") or null on failure
     */
    public function storeUploadedFile(UploadedFile $file, string $directory, ?string $filename = null): ?string;

    /**
     * Store raw contents under the given path (e.g. from API body or generated content).
     *
     * @return string|null  Stored path or null on failure
     */
    public function storeContents(string $contents, string $path): ?string;

    /**
     * Default disk name used for storage (e.g. "public", "s3").
     */
    public function getDisk(): string;
}
