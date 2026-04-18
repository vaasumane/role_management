<?php

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
        Schema::create('family_visited', function (Blueprint $table) {
            $table->id();
            $table->integer('family_id')->nullable();
            $table->date('visited_date')->nullable();
            $table->time('visited_time')->nullable();
            $table->string('visited_location')->nullable();
            $table->index('family_id');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_visited');
    }
};
