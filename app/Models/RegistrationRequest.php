<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Payment;

class RegistrationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'domain',
        'name',
        'email',
        'password',
        'plan_id',
        'status',
    ];

    protected $hidden = [
        'password',
    ];
    
    protected $casts = [
        'password' => 'hashed',
        'status' => \App\Domain\Landlord\Enums\RegistrationRequestStatusEnum::class,
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function getLatestPayment()
    {
        $slug = explode('.', $this->domain)[0];
        return Payment::where('tenant_id', $slug)->latest()->first();
    }
}
