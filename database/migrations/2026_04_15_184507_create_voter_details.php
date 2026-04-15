<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('voter_details', function (Blueprint $table) {
            $table->id();
            $table->integer('part_id')->nullable();
            $table->integer('acid')->nullable();
            $table->string('slnoinpart')->nullable();
            $table->integer('section_no');
            $table->string('house_no')->nullable();
            $table->string('applicant_full_name')->nullable();
            $table->string('applicant_first_name')->nullable();
            $table->string('applicant_last_name')->nullable();
            $table->integer('age')->nullable();
            $table->enum('gender', ['F', 'M', 'O'])
                ->nullable()
                ->comment('F=>Female, M=>Male, O=>Other');
            $table->enum('relation', ['FTHR', 'HSBN', 'MTHR', 'OTHR', 'WIFE'])
                ->nullable()
                ->comment('FTHR=>Father, HSBN=>husband, MTHR =>Mother,OTHR=>Other,WIFE=>WIFE');
            $table->string('realtion_full_name')->nullable();
            $table->string('realtion_last_name')->nullable();
            $table->string('epic_number')->nullable();
            $table->string('v_address')->nullable();
            $table->string('booth_address')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamp('created_at')
                ->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->timestamp('updated_at')
                ->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            // Indexes
            $table->index('updated_by');
            $table->index('part_id');
            $table->index('acid');

            // Foreign Keys
            $table->foreign('updated_by')
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
        Schema::dropIfExists('voter_details');
    }
};
