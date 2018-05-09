<?php
namespace TODONS\system\exception;

/**
 * Exception triggered when ever there is an unpermitted request
 *
 * @package TODONS\system\exception
 */
class PermissionDeniedException extends AbstractException {
	use TAjaxException;

	/**
	 * @param \Exception $previous
	 */
	public function __construct($previous = null) {
		parent::__construct('Access denied', 403, $previous);
	}

	/**
	 * Render the error
	 */
	public function run() {
		$this->render();
	}
}