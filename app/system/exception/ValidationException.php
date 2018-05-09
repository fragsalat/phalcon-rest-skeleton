<?php
namespace TODONS\system\exception;

/**
 * Exception triggered when ever there is something invalid
 *
 * @package TODONS\system\exception
 */
class ValidationException extends AbstractException {
	use TAjaxException;

	/**
	 * @param string $message
	 * @param int $code
	 * @param \Exception $previous
	 */
	public function __construct($message, $code = 422, $previous = null) {
		parent::__construct($message, $code, $previous);
	}

	/**
	 * Render the error
	 */
	public function run() {
		$this->render();
	}
}