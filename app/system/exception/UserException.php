<?php
namespace TODONS\system\exception;

/**
 * This exception is used to simply render a exception to the user
 *
 * @package TODONS\system\exception
 */
class UserException extends AbstractException {
	use TAjaxException;

	/**
	 * Render the exception message
	 */
	public function run() {
		$this->render();
	}
}