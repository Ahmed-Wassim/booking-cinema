<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Home\Booking\Services\Classes;

use App\Models\Tenant\Booking;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Spatie\LaravelPdf\Facades\Pdf;

class TicketService
{
    public function generateQrCode(string $qrCodeContent, int $size = 400, int $margin = 2): string
    {
        return (string) QrCode::format('svg')
            ->size($size)
            ->margin($margin)
            ->generate($qrCodeContent);
    }

    public function generateVoucherPdf(Booking $booking): string
    {
        $booking->loadMissing([
            'customer',
            'showtime.movie',
            'showtime.hall.branch',
            'tickets',
        ]);

        $path = $this->voucherPath($booking);
        $absolutePath = $this->voucherAbsolutePath($booking);
        $directory = dirname($absolutePath);

        File::ensureDirectoryExists($directory);
        File::delete($absolutePath);

        Pdf::view('pdfs.booking-voucher', [
            'booking' => $booking,
        ])
            ->driver('dompdf')
            ->format('a4')
            ->margins(top: 12, right: 12, bottom: 12, left: 12, unit: 'mm')
            ->save($absolutePath);

        return $path;
    }

    public function voucherPath(Booking $booking): string
    {
        $tenantKey = 'central';

        if (function_exists('tenant') && tenancy()->initialized && tenant()) {
            $tenantKey = 'tenant_'.$this->sanitizePathSegment((string) tenant()->getTenantKey());
        }

        $date = now()->format('Y-m-d');

        return $tenantKey.'/'.$date.'/booking-'.$booking->id.'-voucher.pdf';
    }

    public function voucherAbsolutePath(Booking $booking): string
    {
        return base_path('storage/app/private/'.$this->voucherPath($booking));
    }

    protected function sanitizePathSegment(string $value): string
    {
        $sanitized = preg_replace('/[^A-Za-z0-9_-]+/', '_', $value) ?? 'unknown';

        return Str::of($sanitized)->trim('_')->value() ?: 'unknown';
    }
}
