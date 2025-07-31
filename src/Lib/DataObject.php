<?php
/**
* Copyright 2025 oliv (http://opoink.com/)
* Licensed under MIT, see LICENSE.md
*
* this DataObject is like the Magento 2 \Magento\Framework\DataObject
* since the idea came from it
*/
namespace Opoink\Oliv\Lib;

class DataObject {

	/**
	 * Setter/Getter underscore transformation cache
	 *
	 * @var array
	 */
	protected static $_underscoreCache = [];


	/**
	 * Object attributes
	 *
	 * @var array
	 */
	protected $data = [];

	public function __construct(array $data = []){
		$this->data = $data;
	}


	public function setData($key, $value = null){
		if ($key === (array)$key) {
			$this->data = $key;
		} else {
			$this->data[$key] = $value;
		}
		return $this;
	}

	/**
	 * Object data getter
	 * 
	 * @param string $key
	 * @return mixed
	 */
	public function getData($keys = '', $default=null){
		if(empty($keys)){
			return $this->data;
		}

        $keys = explode('/', $keys);
        $data = $this->data;
        foreach ($keys as $key) {
            if ( isset($data[$key]) ) {
                $data = $data[$key];
            } elseif ($data instanceof DataObject) {
                $data = $data->getData($key, $default);
            } else {
                return $default;
            }
        }
        return $data;
	}

	public function unsetData($key = null){
        if ($key === null) {
            $this->setData([]);
        } elseif (is_string($key)) {
            if (array_key_exists($key, $this->data)) {
                unset($this->data[$key]);
            }
        } elseif ($key === (array)$key) {
            foreach ($key as $element) {
                $this->unsetData($element);
            }
        }
        return $this;
    }

	/**
	 * Converts field names for setters and getters
	 *
	 * $this->setSampleMthod($value) === $this->setData('sample_mthod', $value)
	 * Uses cache to eliminate unnecessary preg_replace
	 *
	 * @param string $name
	 * @return string
	 */
	public function _underscore($name){
		if (isset(self::$_underscoreCache[$name])) {
			return self::$_underscoreCache[$name];
		}
		$result = strtolower(trim(preg_replace('/([A-Z]|[0-9]+)/', "_$1", $name), '_'));
		self::$_underscoreCache[$name] = $result;
		return $result;
	}

	/**
	 * @param string $name
	 * @param string $casing strtolower | strtoupper | ucwords | ucfirst
	 * @return string
	 */
	public function camelCaseToSpace($name, $casing='strtolower'){
		$name = $this->_underscore($name);
		$name = str_replace('_', ' ', $name);
		if($casing == 'ucwords'){
			$name = ucwords($name);
		}
		else if($casing == 'strtoupper'){
			$name = strtoupper($name);
		}
		else if($casing == 'ucfirst'){
			$name = ucfirst($name);
		}
		return $name;
	}

	/**
	 * Set/Get attribute wrapper
	 *
	 * @param $method
	 * @param $args
	 * @return  mixed
	 */
	public function __call($method, $args)
	{
		switch (substr($method, 0, 3)) {
			case 'get':
				$key = $this->_underscore(substr($method, 3));
				$index = isset($args[0]) ? $args[0] : null;
				$path = $key;
				if($index){
					$path .= '/' . $index;
				}
				return $this->getData($path);
			case 'set':
				$key = $this->_underscore(substr($method, 3));
				$value = isset($args[0]) ? $args[0] : null;
				return $this->setData($key, $value);
			case 'uns':
				$key = $this->_underscore(substr($method, 3));
				return $this->unsetData($key);
			case 'has':
				$key = $this->_underscore(substr($method, 3));
				return isset($this->data[$key]);
		}
		return null;
	}
}
?>