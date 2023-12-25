<?php

namespace SimonMarcelLinden\JWT\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * User Resource class.
 *
 * This class extends the JsonResource class and is specifically designed for formatting user data
 * into a structured array for JSON responses. It defines how user instances are transformed when
 * being serialized to JSON.
 *
 * @Author: Simon Marcel Linden
 * @Version: 1.0.0
 * @since: 1.0.0
 *
 */
class UserResource extends JsonResource {
	/**
	 * Transform the resource into an array.
	 *
	 * This method takes a user model and converts it into a well-structured array suitable for JSON
	 * serialization. It includes specific user attributes in the array.
	 *
	 * @param \Illuminate\Http\Request $request The incoming HTTP request.
	 * @return array An associative array representing the user, containing specific attributes.
	 */
	public function toArray($request) {
		return [
			'id' => $this->id,              // The user's identifier.
			'firstname' => $this->firstname,// The user's first name.
			'lastname' => $this->lastname,  // The user's last name.
			'email' => $this->email,        // The user's email address.
		];
	}
}
