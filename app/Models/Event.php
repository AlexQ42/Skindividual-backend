<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;
    public function __construct(
        public int          $id,
        public string       $name,
        public string       $place,
        public DateTime     $date,
        public SkinType     $skinType,
        public EventType    $eventType,
        public float        $price,
        public int          $availableSpots,
        public String       $description
    )
    {}

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
