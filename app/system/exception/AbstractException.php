<?php
namespace TODONS\system\exception;

/**
 * Abstract exception class used within this application
 *
 * @package TODONS\system\exception
 */
abstract class AbstractException extends \Exception {

	/**
	 * Create new exception
	 *
	 * @param string $message
	 * @param int $code
	 * @param \Exception $previous
	 */
	public function __construct($message, $code = 503, $previous = null) {
		parent::__construct($message, $code, $previous);
		// Run and exit without any system error to prevent duplicate error handling
//		$this->run();
//		exit;
	}

	/**
	 * Run this exception means in general render and/or log the exception
	 */
	abstract public function run();
}