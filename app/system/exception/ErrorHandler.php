<?php
namespace TODONS\system\exception;
use TODONS\system\exception\ErrorException as Error;

/**
 * This class registers error handlers to ensure to render all errors
 */
class ErrorHandler {

	/**
	 * Register error handler and shutdown function to catch all errors
	 */
	public function __construct() {
//		set_error_handler([$this, 'handleError']);
		set_exception_handler([$this, 'handleException']);
		register_shutdown_function([$this, 'onShutdown']);
	}

	/**
	 * Handle error by throwing an exception with this error
	 *
	 * @param int $severity
	 * @param string $message
	 * @param string $file
	 * @param string $line
	 * @throws Error
	 */
	public function handleError($severity, $message, $file, $line) {
		throw new Error($severity, $message, $file, $line);
	}

	/**
	 * Handle global exceptions
	 *
	 * @param \Exception $exception
	 */
	public function handleException($exception) {
		// Wrap exceptions into system exception to render or log correctly
		if (!($exception instanceof AbstractException)) {
			$exception = new SystemException($exception->getMessage(), 503, $exception);
		}
		$exception->run();
	}

	/**
	 * Handle application shutdown to check for not thrown errors
	 *
	 * @throws Error
	 */
	public function onShutdown() {
		$last_error = error_get_last();
		// Check if the last error is critical
		if ($last_error['type'] === E_ERROR) {
			// Forward the error to the error handler
			$this->handleError(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
		}
	}
}