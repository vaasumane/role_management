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
            $table->enum('visited_status',['0','1'])->comment('0=>Not Visited,1=>voter_details')->default(0);
            $table->integer('status')->nullable();
            $table->integer('color')->nullable();
            $table->integer('religions')->nullable();
            $table->integer('castes')->nullable();
            $table->integer('occupations')->nullable();
            $table->integer('educations')->nullable();
            $table->integer('languages')->nullable();
            $table->integer('mobile')->nullable();
            $table->enum('status1',['0','1'])->comment('1=>Accepted,0=>Notice')->default(0);
            $table->enum('status2',['0','1'])->comment('1=>Accepted,0=>Rejected')->default(0);
            $table->enum('status3',['0','1'])->comment('1=>Apeal 1,0=>Rejected')->default(0);
            $table->enum('status4',['0','1'])->comment('1=>Apeal 2,0=>Final Rejected')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voter_details', function (Blueprint $table) {
            $table->dropColumn('visited_status');
             $table->dropColumn('status');
            $table->dropColumn('color');
            $table->dropColumn('religions');
            $table->dropColumn('castes');
            $table->dropColumn('occupations');
            $table->dropColumn('educations');
            $table->dropColumn('languages');
            $table->dropColumn('mobile');
            $table->dropColumn('status1');
            $table->dropColumn('status2');
            $table->dropColumn('status3');
            $table->dropColumn('status4');
        });
    }
};
