<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Dashboard\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Showtime;
use Illuminate\Http\Request;

class ShowtimeOfferController extends Controller
{
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'offer_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $showtime = Showtime::findOrFail($id);
        $showtime->update(['offer_percentage' => $validated['offer_percentage']]);

        return response()->json([
            'message' => 'Showtime offer updated successfully',
            'offer_percentage' => $showtime->offer_percentage,
        ]);
    }

    public function destroy(string $id)
    {
        $showtime = Showtime::findOrFail($id);
        $showtime->update(['offer_percentage' => null]);

        return response()->json(null, 204);
    }
}
