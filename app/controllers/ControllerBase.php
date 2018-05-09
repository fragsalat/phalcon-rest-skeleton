<?php
namespace TODONS\controller;
use Phalcon\Mvc\Controller;
use TODONS\system\http\OptionsResponse;

/**
 * Class ControllerBase
 * @package TODONS\controller
 * @property \TODONS\services\UserService $userService
 * @property \TODONS\services\AuthService $authService
 */
class ControllerBase extends Controller {

    public function beforeExecuteRoute() {
        if (strtoupper($_SERVER['REQUEST_METHOD']) == 'OPTIONS') {
            $response = new OptionsResponse();
            $response->send();
            exit(0);
        }
    }
}
