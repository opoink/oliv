<?php
namespace Plugins\Opoink\Email\Models;

use Illuminate\Support\Facades\Cache;

class Emails extends \Plugins\Opoink\Liv\Models\Model 
{

	const DEFAULT_TEMPLATE_NAME = "default_template";

	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'emails';

	public static function getDefaultTemplate(){
		return self::where('name', self::DEFAULT_TEMPLATE_NAME)->first();
	}

	public static function getTemplate(string $name){
		return self::where('name', $name)->first();
	}
}
