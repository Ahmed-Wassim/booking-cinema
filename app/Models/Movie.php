<?php

namespace App\Models;

use App\Traits\Shared\ActiveTrait;
use App\Traits\Shared\CreatedAtRangeTrait;
use App\Traits\Shared\FilterTrait;
use App\Traits\Shared\SearchTrait;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use ActiveTrait, CreatedAtRangeTrait, FilterTrait, SearchTrait;

    protected $connection = 'central';

    protected $fillable = [
        'supplier_id',
        'external_id',
        'title',
        'overview',
        'poster_path',
        'backdrop_path',
        'release_date',
        'runtime',
        'language',
        'popularity',
    ];

    protected $casts = [
        'release_date' => 'date',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class , 'movie_genre');
    }
}
