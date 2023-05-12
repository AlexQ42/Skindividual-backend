<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    use HasFactory;
    public function __construct(
        public int $id,
        public string $password,
        public string $email,
        public SkinType $skinType,
        public string $firstname,
        public string $lastname
    ) {}

        public function orders(): HasMany
        {
            return $this->hasMany(Order::class);
        }

        public function reviews(): HasMany
        {
            return $this->hasMany(Review::class);
        }

}