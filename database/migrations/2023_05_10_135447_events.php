<?php

use App\Models\EventType;
use App\Models\SkinType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table)
        {
            $table->id();
            $table->string('name');
            $table->string('place');
            $table->dateTime('date');
            $table->enum('skinType', (array_column(SkinType::cases(), 'value')));
            $table->enum('eventType', (array_column(EventType::cases(), 'value')));
            $table->float('price');
            $table->integer('availableSpots');
            $table->string('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
