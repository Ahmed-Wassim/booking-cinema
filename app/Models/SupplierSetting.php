<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierSetting extends Model
{
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
