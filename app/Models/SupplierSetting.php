<?php

namespace App\Models;

use App\Policies\SupplierSettingPolicy;
use App\Traits\Shared\ActiveTrait;
use App\Traits\Shared\CreatedAtRangeTrait;
use App\Traits\Shared\FilterTrait;
use App\Traits\Shared\SearchTrait;

use Illuminate\Database\Eloquent\Model;

#[UsePolicy(SupplierSettingPolicy::class)]
class SupplierSetting extends Model
{
    use ActiveTrait, CreatedAtRangeTrait, FilterTrait, SearchTrait;

    protected $fillable = [
        'supplier_id',
        'key',
        'type',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function getApiKey(): ?string
    {
        return $this->settings['api_key'] ?? null;
    }

    public function getApiUrl(): ?string
    {
        return $this->settings['api_url'] ?? null;
    }

    public function getEnvType(): ?string
    {
        return $this->settings['env_type'] ?? null;
    }
}
