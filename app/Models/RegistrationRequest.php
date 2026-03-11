<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
