<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Plugins\Opoink\Email\Models\Emails AS EmailsModel;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
			$table->string('name', 255);
			$table->string('subject', 255);
			$table->longText('content');
			$table->longText('css')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });

		$emailsModel = new EmailsModel();
		$emailsModel->name = "default_template";
		$emailsModel->subject = "";
		$emailsModel->content = file_get_contents(base_path('plugins/Opoink/Email/resources/views/default_template.blade.php'));
		$emailsModel->save();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emails');
    }
};
