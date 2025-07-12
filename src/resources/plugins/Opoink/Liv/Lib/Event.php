<?php
namespace Plugins\Opoink\Liv\Lib;


use Illuminate\Support\Facades\Cache;

/**
 * we cannot use Illuminate\Support\Facades\Event 
 * so we need to use this event class instead
 */

class Event {

	const CACHE_KEY = "plugin_event_listeners";

	protected $events = null;

	/**
	 * @return array
	 */
	public function getEvents(){
		if(!$this->events){
			$this->events = Cache::get(self::CACHE_KEY);
		}

		return $this->events;
	}

	/**
	 * @param $eventName string
	 * @param $data array
	 */
	public function dispatch(string $eventName, array $data = []) {
		$events = $this->getEvents();

		if(isset($events[$eventName])){
			foreach ($events[$eventName] as $event) {
				$dataObject = new \Opoink\Oliv\Lib\DataObject($data);

				$listener = app($event['listener']);
				$listener->handle($dataObject);
			}
		}
	}
}
?>