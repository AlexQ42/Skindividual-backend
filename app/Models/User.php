<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    )
    {}
}
