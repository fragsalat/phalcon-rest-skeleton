<?php
namespace TODONS\system\exception;
use Phalcon\DI;
use Phalcon\Logger;

/**
 * Enhance the target exception with function to write exception into log
 *
 * @package TODONS\system\exception
 */
trait TLoggedException {

	/**
	 * Write exception into log
	 */
	protected function log() {
		// Try to get dependency injector
		$di = DI::getDefault();
		if (empty($di)) {
			return;
		}
		// Try to get a logger
		$logger = $di->get('logger');
		if (empty($logger)) {
			return;
		}
		// Log this exception
		$logger->log($this->getMessage() . "\n" . $this->getTraceAsString(), $this->getLogType());
	}

	/**
	 * Retrieve the type for logging this exception
	 *
	 * @return int
	 */
	protected function getLogType() {
		return Logger::CRITICAL;
	}
}