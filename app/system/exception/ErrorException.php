<?php
namespace TODONS\system\exception;
use Phalcon\Logger;

/**
 * This exception gets triggered through ErrorHandler::handleError() when an error is triggered
 *
 * @package TODONS\system\exception
 */
class ErrorException extends SystemException {

	/**
	 * Create error exception
	 *
	 * @param string $severity
	 * @param int $message
	 * @param \Exception|null $file
	 * @param $line
	 * @param array $stack
	 */
	public function __construct($severity, $message, $file, $line, array $stack = []) {
		parent::__construct($message, 503);
	}

	/**
	 * Retrieve the type for logging exception
	 *
	 * @return int
	 */
	public function getLogType() {
		return Logger::ERROR;
	}
}