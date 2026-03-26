<?php

namespace App\Http\Controllers\Tenant\Dashboard\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Ticket;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TicketController extends Controller
{
    public function validate(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string|exists:tickets,qr_code',
        ]);

        $ticket = Ticket::where('qr_code', $request->qr_code)->first();

        // 1. Check if already used
        if ($ticket->used_at !== null) {
            return response()->json([
                'status' => 'already_used',
                'message' => 'This ticket has already been used.'
            ], 400);
        }

        // 2. Check showtime not expired
        $showtime = $ticket->booking->showtime;

        if ($showtime->start_time->isPast() && now()->diffInHours($showtime->start_time) > 4) {
            return response()->json([
                'status' => 'expired',
                'message' => 'Showtime has expired.'
            ], 400);
        }

        // 3. Mark as used
        $ticket->update([
            'used_at' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Ticket validated successfully!',
            'ticket' => [
                'ticket_number' => $ticket->ticket_number,
                'seat' => $ticket->seat_label,
                'used_at' => $ticket->used_at,
            ]
        ]);
    }
}
