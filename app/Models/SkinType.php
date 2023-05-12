<?php

namespace App\Models;

enum SkinType: string
{
    case None = 'none';
    case Dry = 'dry';
    case Oily = 'oily';
    case Combination = 'combination';
    case Normal = 'normal';
    case Sensitive = 'sensitive';
}

