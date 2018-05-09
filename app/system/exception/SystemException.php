<?php
namespace TODONS\system\exception;

/**
 * Critical system exception with response and logging
 *
 * @package TODONS\system\exception
 */
class SystemException extends AbstractException {
	use TAjaxException;
	use TLoggedException;

	/**
	 * Write exception to output and log
	 */
	public function run() {
		$this->render();
		$this->log();
	}
}