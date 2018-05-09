<?php
namespace TODONS\system\http;

use Phalcon\Http\Response;

/**
 * This class is used to easily create a options response.
 *
 * That option response is required for cross site http requests.
 * Find a briefly introduction especially chapter about Preflighted requests
 *
 * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Access_control_CORS
 */
class OptionsResponse extends Response {

	/**
	 * Initialize new ajax response
	 */
	public function __construct() {
		parent::__construct(null, 200, 'OK');
		$this->setContentType('application/json', 'utf-8');
		$this->setHeader('Access-Control-Allow-Origin', $_SERVER['HTTP_ORIGIN']);
		$this->setHeader('Access-Control-Allow-Credentials', 'true');
		$this->setHeader('Access-Control-Allow-Methods', 'POST, GET, OPTIONS');
		$this->setHeader('Access-Control-Allow-Headers', 'DNT,X-Mx-ReqToken,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type');
	}
}