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
        Schema::create('listing_bookmark', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('admins_id')->unsigned()->default(null)->nullable();
            $table->longText('namespace');
            $table->longText('config');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

			
			$table->index('admins_id');
			$table->foreign('admins_id')->references('id')->on('admins')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listing_bookmark');
    }
};
