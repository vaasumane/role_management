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
        Schema::create('user_part_assembly_mapping', function (Blueprint $table) {
            $table->id();

            $table->integer('user_id')->nullable();
            $table->integer('part_id')->nullable();
            $table->integer('acid')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('user_id');
            $table->index('part_id');
            $table->index('acid');

            // Foreign Keys
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            $table->foreign('part_id')
                ->references('id')
                ->on('parts')
                ->cascadeOnDelete();

            $table->foreign('acid')
                ->references('id')
                ->on('assembly_constituencies')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_part_assembly_mapping');
    }
};
