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
        Schema::create('asset_status_logs', function (Blueprint $table) {
            $table->id();
            $table->string('asset_no');
            $table->text('description')->nullable();
            $table->string('next_due_date');
            $table->time('change_time');
            $table->date('change_date');
            $table->enum('old_status', ['delivered', 'work underway', 'tagged out – further work found', 'work completed, ready for pickup', 'no show', 'no status yet', 'mud buildup unsafe', 'late delivery']);
            $table->enum('new_status', ['delivered', 'work underway', 'tagged out – further work found', 'work completed, ready for pickup', 'no show', 'no status yet', 'mud buildup unsafe', 'late delivery']);
            $table->enum('asset_type', ['lv', 'lt', 'tk', 'fm', 'pm']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_status_logs');
    }
};
