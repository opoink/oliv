<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('admin_type')->nullable();
            $table->bigInteger('admin_user_role_id')->unsigned()->default(null)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

			
			$table->index('admin_user_role_id');
			$table->foreign('admin_user_role_id')->references('id')->on('admin_user_roles')->onDelete('set null');
        });

		$users = [
			[
				'firstname' => 'Admin',
				'lastname' => 'Admin',
				'email' => 'admin@domain.com',
				'password' => Hash::make('admin'),
				'admin_type' => 'super_admin',
				'admin_user_role_id' => null
			]
		];
		
		foreach ($users as $user) {
			DB::table('admins')->insertGetId($user);
		}
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
