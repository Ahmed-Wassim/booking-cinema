<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            background: #0b0f1a;
            color: #d1d5db;
            font-size: 12px;
            padding: 0;
        }

        .page {
            background: #0b0f1a;
            padding: 16px;
        }

        .card {
            background: #111827;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            overflow: hidden;
        }

        /* ── HEADER ── */
        .header {
            background: #1a1000;
            border-bottom: 2px solid #f59e0b;
            padding: 14px 18px 12px;
        }

        .header-table {
            width: 100%;
        }

        .header-title {
            font-size: 22px;
            font-weight: bold;
            color: #f59e0b;
            letter-spacing: 1px;
        }

        .header-sub {
            font-size: 9px;
            color: #9ca3af;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 2px;
        }

        .booking-id-label {
            font-size: 8px;
            color: #9ca3af;
            letter-spacing: 2px;
            text-transform: uppercase;
            text-align: right;
        }

        .booking-id-val {
            font-size: 18px;
            font-weight: bold;
            color: #fcd34d;
            text-align: right;
        }

        /* ── SPROCKET STRIP ── */
        .sprocket-strip {
            background: #1a1000;
            padding: 5px 18px;
            border-bottom: 1px solid #2d1f00;
        }

        .sprocket-table {
            width: 100%;
        }

        .sprocket-hole {
            width: 12px;
            height: 8px;
            border: 1.5px solid #f59e0b;
            display: inline-block;
            margin-right: 7px;
            opacity: 0.35;
        }

        /* ── MOVIE TITLE BLOCK ── */
        .movie-block {
            padding: 18px 18px 0;
            border-bottom: 1px dashed #374151;
        }

        .movie-title {
            font-size: 26px;
            font-weight: bold;
            color: #f9fafb;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }

        .meta-table {
            width: 100%;
            margin-bottom: 14px;
        }

        .pill {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .pill-gold {
            background: #2d1f00;
            border: 1px solid #78450a;
            color: #fcd34d;
        }

        .pill-gray {
            background: #1f2937;
            border: 1px solid #374151;
            color: #9ca3af;
        }

        /* ── SECTION DIVIDER ── */
        .divider-row {
            background: #0b0f1a;
            border-top: 1px solid #1f2937;
            border-bottom: 1px solid #1f2937;
            padding: 5px 0;
            text-align: center;
        }

        .divider-text {
            font-size: 8px;
            color: #6b7280;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        /* ── CUSTOMER + INFO SECTION ── */
        .section {
            padding: 14px 18px;
            border-bottom: 1px solid #1f2937;
        }

        .customer-box {
            background: #1a1f2e;
            border: 1px solid #2d3748;
            border-left: 3px solid #f59e0b;
            border-radius: 6px;
            padding: 10px 12px;
            margin-bottom: 12px;
        }

        .customer-name {
            font-size: 13px;
            font-weight: bold;
            color: #f9fafb;
            margin-bottom: 3px;
        }

        .customer-contact {
            font-size: 10px;
            color: #9ca3af;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-label {
            font-size: 8px;
            color: #6b7280;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding-bottom: 1px;
        }

        .info-value {
            font-size: 12px;
            font-weight: bold;
            color: #f9fafb;
            padding-bottom: 10px;
        }

        .info-value-gold {
            color: #fcd34d;
        }

        .info-value-green {
            color: #4ade80;
        }

        /* ── TICKETS ── */
        .tickets-section {
            padding: 14px 18px 16px;
        }

        .tickets-label {
            font-size: 8px;
            color: #6b7280;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .ticket-box {
            background: #0f1724;
            border: 1px solid #374151;
            border-left: 3px solid #f59e0b;
            border-radius: 6px;
            padding: 10px 12px;
            margin-bottom: 8px;
        }

        .ticket-table {
            width: 100%;
        }

        .ticket-qr-cell {
            width: 80px;
            vertical-align: top;
            padding-right: 12px;
        }

        .ticket-qr-wrap {
            background: #ffffff;
            padding: 4px;
            border-radius: 4px;
            width: 72px;
        }

        .ticket-qr-wrap img {
            width: 64px;
            height: 64px;
            display: block;
        }

        .ticket-number {
            font-size: 14px;
            font-weight: bold;
            color: #f59e0b;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .seat-badge {
            display: inline-block;
            background: #2d1f00;
            border: 1px solid #78450a;
            color: #fcd34d;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .ticket-hint {
            font-size: 9px;
            color: #6b7280;
        }

        /* ── FOOTER ── */
        .footer {
            background: #0f1421;
            border-top: 1px solid #2d1f00;
            padding: 12px 18px;
        }

        .footer-table {
            width: 100%;
            margin-bottom: 10px;
        }

        .notice-icon {
            font-size: 14px;
            vertical-align: middle;
        }

        .notice-title {
            font-size: 10px;
            font-weight: bold;
            color: #d1d5db;
        }

        .notice-text {
            font-size: 9px;
            color: #9ca3af;
            margin-top: 2px;
        }

        .footer-bottom-table {
            width: 100%;
            border-top: 1px solid #1f2937;
            padding-top: 8px;
        }

        .footer-brand {
            font-size: 13px;
            font-weight: bold;
            color: #78450a;
            letter-spacing: 2px;
        }

        .footer-fine {
            font-size: 8px;
            color: #374151;
            letter-spacing: 1px;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="page">
        <div class="card">

            {{-- ── HEADER ── --}}
            <div class="header">
                <table class="header-table">
                    <tr>
                        <td style="vertical-align: top;">
                            <div class="header-title">&#127916; CinePass</div>
                            <div class="header-sub">Official Booking Voucher</div>
                        </td>
                        <td style="vertical-align: top; text-align: right; width: 140px;">
                            <div class="booking-id-label">Booking ID</div>
                            <div class="booking-id-val">#{{ $booking->id }}</div>
                        </td>
                    </tr>
                </table>
            </div>

            {{-- ── SPROCKET ── --}}
            <div class="sprocket-strip">
                <span class="sprocket-hole"></span><span class="sprocket-hole"></span><span
                    class="sprocket-hole"></span>
                <span class="sprocket-hole"></span><span class="sprocket-hole"></span><span
                    class="sprocket-hole"></span>
                <span class="sprocket-hole"></span><span class="sprocket-hole"></span><span
                    class="sprocket-hole"></span>
            </div>

            {{-- ── MOVIE BLOCK ── --}}
            <div class="movie-block">
                <div class="movie-title">{{ $booking->showtime->movie->title }}</div>
                <table class="meta-table">
                    <tr>
                        <td style="padding-right: 6px; white-space: nowrap;">
                            <span class="pill pill-gold">&#128197;
                                {{ $booking->showtime->start_time->format('d M Y') }}</span>
                        </td>
                        <td style="padding-right: 6px; white-space: nowrap;">
                            <span class="pill pill-gold">&#128336;
                                {{ $booking->showtime->start_time->format('h:i A') }}</span>
                        </td>
                        <td style="padding-right: 6px; white-space: nowrap;">
                            <span class="pill pill-gray">{{ $booking->showtime->hall->branch->name ?? 'N/A' }}</span>
                        </td>
                        <td style="white-space: nowrap;">
                            <span class="pill pill-gray">{{ $booking->showtime->hall->name ?? 'N/A' }}</span>
                        </td>
                    </tr>
                </table>
            </div>

            {{-- ── DIVIDER ── --}}
            <div class="divider-row">
                <span class="divider-text">&#9650; PRESENT AT ENTRANCE &#9650;</span>
            </div>

            {{-- ── CUSTOMER + INFO ── --}}
            <div class="section">
                <div class="customer-box">
                    <div class="customer-name">{{ $booking->customer?->name ?? 'Guest' }}</div>
                    <div class="customer-contact">
                        {{ $booking->customer?->email ?? 'N/A' }}
                        &nbsp;&bull;&nbsp;
                        {{ $booking->customer?->phone_country_code }} {{ $booking->customer?->phone ?? 'N/A' }}
                    </div>
                </div>

                <table class="info-table">
                    <tr>
                        <td style="width: 25%;">
                            <div class="info-label">Date</div>
                            <div class="info-value info-value-gold">
                                {{ $booking->showtime->start_time->format('d M Y') }}</div>
                        </td>
                        <td style="width: 25%;">
                            <div class="info-label">Showtime</div>
                            <div class="info-value info-value-gold">
                                {{ $booking->showtime->start_time->format('h:i A') }}</div>
                        </td>
                        <td style="width: 25%;">
                            <div class="info-label">Branch</div>
                            <div class="info-value">{{ $booking->showtime->hall->branch->name ?? 'N/A' }}</div>
                        </td>
                        <td style="width: 25%;">
                            <div class="info-label">Hall</div>
                            <div class="info-value">{{ $booking->showtime->hall->name ?? 'N/A' }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="info-label">Tickets</div>
                            <div class="info-value info-value-gold">{{ $booking->tickets->count() }} Seat(s)</div>
                        </td>
                        <td>
                            <div class="info-label">Status</div>
                            <div class="info-value info-value-green">&#10003; Confirmed</div>
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </table>
            </div>

            {{-- ── DIVIDER ── --}}
            <div class="divider-row">
                <span class="divider-text">&#9660; TICKETS BELOW &#9660;</span>
            </div>

            {{-- ── TICKETS ── --}}
            <div class="tickets-section">
                <div class="tickets-label">&#127915; Scan Each Ticket Separately</div>

                @foreach ($booking->tickets as $ticket)
                    <div class="ticket-box">
                        <table class="ticket-table">
                            <tr>
                                <td class="ticket-qr-cell">
                                    <div class="ticket-qr-wrap">
                                        @php
                                            $qrImage = app(\App\Domain\Tenant\Home\Booking\Services\Classes\TicketService::class)
                                                ->generateQrCode($ticket->qr_code);
                                        @endphp
                                        <img src="data:image/svg+xml;base64,{{ base64_encode($qrImage) }}" width="64"
                                            height="64">
                                    </div>
                                </td>
                                <td style="vertical-align: top;">
                                    <div class="ticket-number">&#127903; TICKET #{{ $ticket->ticket_number }}</div>
                                    <div class="seat-badge">&#128186; Seat {{ $ticket->seat_label ?? 'N/A' }}</div>
                                    <div class="ticket-hint">Scan QR at the entrance gate</div>
                                </td>
                            </tr>
                        </table>
                    </div>
                @endforeach
            </div>

            {{-- ── FOOTER ── --}}
            <div class="footer">
                <table class="footer-table">
                    <tr>
                        <td style="width: 50%; vertical-align: top; padding-right: 12px;">
                            <span class="notice-icon">&#9200;</span>
                            <div class="notice-title">Arrive Early</div>
                            <div class="notice-text">Please arrive at least 15 minutes before showtime.</div>
                        </td>
                        <td style="width: 50%; vertical-align: top;">
                            <span class="notice-icon">&#127903;</span>
                            <div class="notice-title">One Scan Per Ticket</div>
                            <div class="notice-text">Each ticket must be scanned separately at the gate.</div>
                        </td>
                    </tr>
                </table>
                <table class="footer-bottom-table">
                    <tr>
                        <td>
                            <div class="footer-brand">CINEPASS</div>
                        </td>
                        <td>
                            <div class="footer-fine">NON-TRANSFERABLE &nbsp;&bull;&nbsp; VALID FOR ONE USE</div>
                        </td>
                    </tr>
                </table>
            </div>

        </div>
    </div>
</body>

</html>