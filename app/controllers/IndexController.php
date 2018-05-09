<?php
namespace TODONS\controller;
use TODONS\system\http\AjaxResponse;

class IndexController extends ControllerBase {

    public function indexAction() {
        return new AjaxResponse(true);
    }
}

