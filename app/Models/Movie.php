<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = [
        'supplier_id',
        'external_id',
        'title',
        'overview',
        'poster_path',
        'backdrop_path',
        'local_poster_path',
        'local_backdrop_path',
        'release_date',
        'runtime',
        'language',
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
        return $this->belongsToMany(Genre::class, 'movie_genre');
    }
}
