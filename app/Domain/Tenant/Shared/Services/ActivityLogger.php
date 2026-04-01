<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Shared\Services;

use Illuminate\Support\Str;

class ActivityLogger
{
    public static function log(string $eventName, mixed $subject = null, array $properties = []): void
    {
        $guestId = session()->get('guest_id');
        if (! $guestId && ! auth()->check()) {
            $guestId = (string) Str::uuid();
            session()->put('guest_id', $guestId);
            session()->save();
        }

        $defaultProps = [
            'ip_address' => request()->ip(),
            'session_id' => session()->getId(),
        ];

        if (! auth()->check()) {
            $defaultProps['guest_id'] = $guestId;
        }

        $allProperties = array_merge($defaultProps, $properties);

        $logger = activity()
            ->event($eventName)
            ->withProperties($allProperties);

        if ($subject) {
            $logger->performedOn($subject);
        }

        if (auth()->check()) {
            $logger->causedBy(auth()->user());
        }

        $logger->log($eventName);
    }
}
