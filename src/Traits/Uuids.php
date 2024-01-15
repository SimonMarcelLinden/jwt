<?php

namespace SimonMarcelLinden\JWT\Traits;

use Illuminate\Support\Str;

/**
 * Uuids Trait.
 *
 * This trait is used to override the default incrementing behavior of Eloquent model IDs with UUIDs.
 * It includes methods to automatically generate UUIDs for model instances and to define the nature
 * of the model's primary key.
 *
 * @Author: Simon Marcel Linden
 * @Version: 1.0.0
 * @since: 1.0.0
 */
trait Uuids {

	/**
	 * Boot method for the trait.
	 *
	 * This method is called during the booting process of the model. It sets up a listener on the
	 * 'creating' event of a model, ensuring that a UUID is automatically generated and assigned as
	 * the primary key if it is not already set.
	 */
	protected static function bootUuids() {
		static::creating(function ($model) {
			if (empty($model->{$model->getKeyName()})) {
				$model->{$model->getKeyName()} = Str::uuid()->toString();
			}
		});
	}

	/**
	 * Indicates if the IDs are incrementing.
	 *
	 * Overriding this method to return false ensures that the Eloquent model does not expect
	 * auto-incrementing IDs, which is necessary when using UUIDs.
	 *
	 * @return bool False to indicate that IDs are not auto-incrementing.
	 */
	public function getIncrementing() {
		return false;
	}

	/**
	 * Get the auto-incrementing key type.
	 *
	 * Overriding this method to return 'string' changes the expected data type of the primary key
	 * in the Eloquent model, aligning with the UUIDs format.
	 *
	 * @return string The data type of the primary key, which is 'string' for UUIDs.
	 */
	public function getKeyType() {
		return 'string';
	}
}
