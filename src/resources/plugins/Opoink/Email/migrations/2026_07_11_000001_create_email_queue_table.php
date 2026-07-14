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
        Schema::create('email_queue', function (Blueprint $table) {
            $table->id();
			$table->string('recipient', 255);
			$table->string('subject', 255);
			$table->longText('body');
			$table->string('status', 255)->default(\Plugins\Opoink\Email\Lib\Option\EmailQueueOption::PENDING);
			$table->timestamp('scheduled_at')->nullable();
			$table->timestamp('sent_at')->nullable();
			$table->integer('attempts')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_queue');
    }
};
