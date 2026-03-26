<?php

namespace App\Models\Tenant;

use App\Traits\Shared\ActiveTrait;
use App\Traits\Shared\CreatedAtRangeTrait;
use App\Traits\Shared\FilterTrait;
use App\Traits\Shared\SearchTrait;

use App\Models\Movie as LandlordMovie;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Movie extends Model
{
    use ActiveTrait, CreatedAtRangeTrait, FilterTrait, SearchTrait;

    protected $fillable = [
        'movie_id',
        'title',
        'poster',
        'runtime',
        'status',
    ];

    public function showtimes(): HasMany
    {
        return $this->hasMany(Showtime::class);
    }

    public function landlordMovie(): ?LandlordMovie
    {
        return Cache::remember("movie_{$this->movie_id}", 3600, function () {

            return LandlordMovie::find($this->movie_id);
        });
    }
}
