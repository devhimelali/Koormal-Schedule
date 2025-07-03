<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('asset_no');
            $table->string('department');
            $table->text('description');
            $table->string('next_due_date');
            $table->enum('status', ['delivered', 'work underway', 'tagged out – further work found', 'work completed, ready for pickup', 'no show', 'not yet touched', 'mud buildup unsafe', 'late delivery'])->default('not yet touched');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
