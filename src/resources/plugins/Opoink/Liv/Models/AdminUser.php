<?php
namespace Plugins\Opoink\Liv\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AdminUser extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admins';

	/**
	 * Get the role associated with the user.
	 */
	public function admin_user_role(): HasOne
	{
		return $this->hasOne(AdminsRoles::class, 'id', 'admin_user_role_id');
	}
}
