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
        Schema::create('admin_user_roles_resource', function (Blueprint $table) {
            $table->id();
            $table->string('resource');
            $table->bigInteger('admin_user_role_id')->unsigned()->default(null)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
			
			$table->index('admin_user_role_id');
			$table->foreign('admin_user_role_id')->references('id')->on('admin_user_roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_user_roles_resource');
    }
};
