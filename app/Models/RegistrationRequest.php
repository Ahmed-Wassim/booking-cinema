<?php

namespace App\Models;

use App\Traits\Shared\ActiveTrait;
use App\Traits\Shared\CreatedAtRangeTrait;
use App\Traits\Shared\FilterTrait;
use App\Traits\Shared\SearchTrait;

use App\Domain\Landlord\Enums\PaymentStatusEnum;
use App\Domain\Landlord\Enums\RegistrationRequestStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrationRequest extends Model
{
    use ActiveTrait, CreatedAtRangeTrait, FilterTrait, SearchTrait;

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
        'status' => RegistrationRequestStatusEnum::class,
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getPaidPayment()
    {
        return $this->payments()
            ->where('status', PaymentStatusEnum::PAID)
            ->latest()
            ->first();
    }
}
