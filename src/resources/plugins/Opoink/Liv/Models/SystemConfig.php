<?php
namespace Plugins\Opoink\Liv\Models;


class SystemConfig extends Model 
{

	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_config';

	protected $fillable = [
		"id",
		'path',
		'value',
	];
}
