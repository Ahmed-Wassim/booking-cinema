<?php

declare(strict_types=1);

namespace App\Domain\Shared\FileStorage\Services;

use App\Domain\Shared\FileStorage\Contracts\FileStorageServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileStorageService implements FileStorageServiceInterface
{
    public function __construct(
        protected string $disk = 'public',
        protected int $timeout = 15
    ) {
    }

    public function storeFromUrl(string $url, string $path): ?string
    {
        try {
            $response = Http::timeout($this->timeout)->get($url);
            if (!$response->successful()) {
                Log::warning('FileStorageService: request failed', ['url' => $url, 'status' => $response->status()]);
                return null;
            }
            Storage::disk($this->disk)->put($path, $response->body());
            return $path;
        } catch (\Throwable $e) {
            Log::warning('FileStorageService: failed to download/store', ['url' => $url, 'error' => $e->getMessage()]);
            return null;
        }
    }

    public function downloadAndStore(string $url, string $directory, ?string $filename = null): ?string
    {
        if ($filename === null) {
            $filename = basename(parse_url($url, PHP_URL_PATH)) ?: 'file_' . uniqid();
        }
        $path = rtrim($directory, '/') . '/' . ltrim($filename, '/');
        return $this->storeFromUrl($url, $path);
    }

    public function storeUploadedFile(UploadedFile $file, string $directory, ?string $filename = null): ?string
    {
        try {
            if ($filename === null) {
                $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                    . '_' . uniqid()
                    . '.' . ($file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'bin');
            }
            $path = Storage::disk($this->disk)->putFileAs(
                $directory,
                $file,
                $filename
            );
            return $path ?: null;
        } catch (\Throwable $e) {
            Log::warning('FileStorageService: failed to store uploaded file', [
                'directory' => $directory,
                'original_name' => $file->getClientOriginalName(),
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    public function storeContents(string $contents, string $path): ?string
    {
        try {
            Storage::disk($this->disk)->put($path, $contents);
            return $path;
        } catch (\Throwable $e) {
            Log::warning('FileStorageService: failed to store contents', [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    public function getDisk(): string
    {
        return $this->disk;
    }
}
