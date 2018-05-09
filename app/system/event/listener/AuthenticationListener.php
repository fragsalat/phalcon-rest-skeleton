<?php
namespace TODONS\system\event\listener;
use TODONS\system\exception\PermissionDeniedException;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\User\Plugin;

/**
 * This class listen on several events to check if the session is authenticated
 *
 * @property \TODONS\services\AuthService $authService
 * @package TODONS\system\event\listener
 */
class AuthenticationListener extends Plugin {

	/**
	 * Check permissions before action is dispatched
	 *
	 * @param Event $event
	 * @param Dispatcher $dispatcher
	 * @return bool
	 * @throws PermissionDeniedException
	 */
	public function beforeDispatch(Event $event, Dispatcher $dispatcher) {
		// Get annotations for controller action
		$controller = $dispatcher->getControllerClass();
		$action = $dispatcher->getActiveMethod();
		$annotations = $this->annotations->getMethod($controller, $action);
		// If there is no annotation go forward
		if (!$annotations->has('authenticated')) {
			return true;
		}
		// Check the annotation argument to determine if the user must be authenticated or not
		// Default value is true so @authenticated is same like @authenticated(true)
		$authenticated = $annotations->get('authenticated');
		$expected = $authenticated->hasArgument(0) ? $authenticated->getArgument(0) : true;
		// Throw permission exception if this request authentication doesn't match the expected result
		if ($this->authService->isAuthenticated() !== $expected) {
			throw new PermissionDeniedException();
		}

		return true;
	}
}