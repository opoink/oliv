<?php
namespace Plugins\Opoink\Liv\Models;

class ListingBookmark extends Model 
{

	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'listing_bookmark';

	public static function getBookmark(string $namespace, ?callable $callback=null){
		$user = auth()->guard('admin')->user();
		$qry = self::where('namespace', $namespace);
		$qry->where('admins_id', $user->id);
		$bookmark = $qry->first();

		if(!$bookmark && $callback){
			return $callback($user);
		}

		return $qry->first();
	}

	public static function createBookmark(int $adminsId, string $namespace, string $config){
		$model = new self();
		$model->admins_id = $adminsId;
		$model->namespace = $namespace;
		$model->config = $config;
		$model->save();

		return $model;
	}
}
