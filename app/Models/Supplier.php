<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'key',
        'name',
        'type',
        'status',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function setting()
    {
        return $this->hasOne(SupplierSetting::class);
    }

    public function movies()
    {
        return $this->hasMany(Movie::class);
    }
}
