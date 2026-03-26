<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Home\Booking\Services\Classes;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketService
{
    public function generateQrCode(string $qrCodeContent, int $size = 400, int $margin = 2): string
    {
        return (string) QrCode::format('svg')
            ->size($size)
            ->margin($margin)
            ->generate($qrCodeContent);
    }
}
