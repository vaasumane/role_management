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
        Schema::create('voter_visited', function (Blueprint $table) {
            $table->id();
            $table->integer('voter_id')->nullable();
            $table->date('visited_date')->nullable();
            $table->time('visited_time')->nullable();
            $table->string('visited_location')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();

            $table->timestamps();
            $table->index('voter_id');
            
        });
        Schema::table('voter_details', function (Blueprint $table) {
            $table->enum('visited_flg', ['1', '2'])->comment('1=>Family', '2=>Voter')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voted_visited');
         Schema::table('voter_details', function (Blueprint $table) {
            $table->dropColumn('visited_flg');
        });
    }
};
