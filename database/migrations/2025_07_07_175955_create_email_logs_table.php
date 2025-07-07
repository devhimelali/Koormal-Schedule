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
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->string('asset_no');
            $table->string('department')->nullable();
            $table->text('description')->nullable();
            $table->string('next_due_date')->nullable();
            $table->date('sent_date');
            $table->time('sent_time');
            $table->longText('email_body');
            $table->boolean('is_sent')->default(false)->comment('false=not sent, true=sent');
            $table->string('recipient_email');
            $table->text('email_subject');
            $table->enum('asset_type', ['lv', 'lt', 'tk', 'fm', 'pm']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};
