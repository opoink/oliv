<?php
namespace Plugins\Opoink\Liv\Models;

use Illuminate\Support\Facades\DB;
use Plugins\Opoink\Liv\Lib\Facades\Event;

class Model extends \Illuminate\Database\Eloquent\Model {

	/**
	 * Save the model to the database without raising any events.
	 *
	 * @param  array  $options
	 * @return bool
	 */
	public function saveQuietly(array $options = [])
	{
		return static::withoutEvents(fn () => $this->save($options));
	}

	public function beforeSave(){

	}

	public function afterSave(){
		
	}

	/**
	 * Save the model to the database.
	 *
	 * @param  array  $options
	 * @return bool
	 */
	public function save(array $options = [])
	{
		$saved = false;
		DB::beginTransaction();
		try {
			$eventName = 'db_' . $this->table . '_save_';

			$this->beforeSave();

			Event::dispatch($eventName . 'before', ['model' => $this]);
			$saved = parent::save($options);
			Event::dispatch($eventName . 'after', ['model' => $this]);

			$this->afterSave();

			DB::commit();
			Event::dispatch($eventName . 'commit_after', ['model' => $this]);
			Event::dispatch('db_model_commit_after', ['model' => $this]);


		} catch (\Exception $e) {
			DB::rollback();
			throw new \Exception($e->getMessage(), 500);
		}

		return $saved;
	}



	public function beforeDelete(){

	}

	public function afterDelete(){
		
	}

    /**
     * Delete the model from the database without raising any events.
     * @return bool
     */
    public function deleteQuietly()
    {
        return static::withoutEvents(fn () => $this->delete());
    }

	/**
     * Delete the model from the database.
     * @return bool|null
     */
    public function delete()
    {
		DB::beginTransaction();
		try {
			$eventName = 'db_' . $this->table . '_delete_';

			$this->beforeDelete();
			Event::dispatch($eventName . 'before', ['model' => $this]);
			$model_data = $this->getAttributes();
			parent::delete();
			Event::dispatch($eventName . 'after', ['model' => $this]);
			$this->afterDelete();

			DB::commit();
			Event::dispatch($eventName . 'commit_after', ['model' => $model_data]);
			Event::dispatch('db_model_commit_after', ['model' => $model_data]);

		} catch (\Exception $e) {
			DB::rollback();
			throw new \Exception($e->getMessage(), 500);
		}
	}
}

?>