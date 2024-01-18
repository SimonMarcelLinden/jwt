<?php

namespace SimonMarcelLinden\JWT\Exceptions;

use Exception;

/**
 * ResponseException class.
 *
 * This custom exception class is used for handling exceptions with HTTP responses in the JWT context.
 * It extends the base Exception class, adding functionality for HTTP status codes and custom JSON responses.
 *
 * @Author: Simon Marcel Linden
 * @Version: 1.0.0
 * @since: 1.0.0
 */
class ResponseException extends Exception {

	protected $message;     // The exception message.
	protected $statusCode;  // The HTTP status code associated with the exception.

	/**
	 * Constructor for ResponseException.
	 *
	 * Initializes a new instance of the ResponseException with a specific message and HTTP status code.
	 *
	 * @param string $message The message for the exception.
	 * @param int $statusCode The HTTP status code for the response. Defaults to 400.
	 */
	public function __construct(string $message = "", int $statusCode = 400) {
		parent::__construct($message);
		$this->statusCode = $statusCode;
		$this->message = $message;
	}

	/**
	 * Get the HTTP status code.
	 *
	 * @return int The HTTP status code associated with the exception.
	 */
	public function getStatusCode() {
		return $this->statusCode;
	}

	/**
	 * Override the getMessage method to return the exception message.
	 *
	 * @return string The exception message.
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * Get a string representation of the HTTP status code.
	 *
	 * @return string The HTTP status code as a string.
	 */
	public function getStatusCodeString() {
		return (string) $this->statusCode;
	}

	/**
	 * String representation of the exception.
	 *
	 * @return string The exception details as a string.
	 */
	public function __toString() {
		return __CLASS__ . ": [{$this->statusCode}]: {$this->message}\n";
	}

	/**
	 * Render the exception into an HTTP response.
	 *
	 * @param \Illuminate\Http\Request $request The request object.
	 * @return \Illuminate\Http\JsonResponse A JSON response with the exception message and status code.
	 */
	public function render($request) {
		return response()->json(['message' => $this->message], $this->statusCode);
	}

}
