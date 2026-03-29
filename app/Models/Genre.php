<?php

namespace App\Models;

use App\Policies\GenrePolicy;
use App\Traits\Shared\ActiveTrait;
use App\Traits\Shared\CreatedAtRangeTrait;
use App\Traits\Shared\FilterTrait;
use App\Traits\Shared\SearchTrait;

use Illuminate\Database\Eloquent\Model;

#[UsePolicy(GenrePolicy::class)]
class Genre extends Model
{
    use ActiveTrait, CreatedAtRangeTrait, FilterTrait, SearchTrait;

    protected $fillable = [
        'external_id',
        'name',
    ];

    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'movie_genre');
    }
}
