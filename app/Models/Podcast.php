<?php

namespace App\Models;

use App\Models\Episode;
use App\Models\ListeningParty;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Podcast extends Model
{
    /** @use HasFactory<\Database\Factories\PodcastFactory> */
    use HasFactory;
    protected $guarded = ['id'];

    public function episodes(): HasMany
    {
        return $this->hasMany(Episode::class);
    }

    public function listeningParties(): HasManyThrough
    {
        return $this->hasManyThrough(ListeningParty::class, Episode::class);
    }
}
