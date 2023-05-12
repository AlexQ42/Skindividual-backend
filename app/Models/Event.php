<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        //public Review[]   $reviews
    )
    {
    }
}
