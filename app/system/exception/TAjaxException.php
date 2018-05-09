<?php
namespace TODONS\system\exception;
use TODONS\system\http\AjaxResponse;

/**
 * Enhance the target exception with a function to render as json response
 *
 * @package TODONS\system\exception
 */
trait TAjaxException {

	/**
	 * Render The exception as json content into output
	 */
	protected function render() {
		$response = new AjaxResponse();
		$response->setStatusCode($this->getCode());
		// Set response using debug mode or normal mode
		if (!defined('DEBUG') || !DEBUG) {
			$response->setJsonContent(['success' => false, 'data' => null, 'error' => $this->getMessage()]);
		}
		else {
			$previous = $this->getPrevious();
			$stack = empty($previous) ? $this->getTrace() : $previous->getTrace();
			$response->setJsonContent(['success' => false, 'data' => null, 'error' => $this->getMessage(), 'stack' => $stack]);
		}
		$response->send();
	}
}