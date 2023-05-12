<?php

namespace App\Models;

enum EventType: string
{
    case Wellness = 'wellness';
    case Counselling = 'counselling';
    case Treatment = 'treatment';
    case Course = 'course';
}

