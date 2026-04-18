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
        Schema::table('voter_details', function (Blueprint $table) {
            $table->date('dob')->nullable();
            $table->integer('family_id')->nullable();

            $table->index('family_id');
            $table->index('status');
            $table->index('color');
            $table->index('religions');
            $table->index('castes');
            $table->index('occupations');
            $table->index('educations');
            $table->index('languages');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voter_details', function (Blueprint $table) {
            $table->dropIndex(['family_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['color']);
            $table->dropIndex(['religions']);
            $table->dropIndex(['castes']);
            $table->dropIndex(['occupations']);
            $table->dropIndex(['educations']);
            $table->dropIndex(['languages']);
            $table->dropColumn('dob');
            $table->dropColumn('family_id');

        });
    }
};
