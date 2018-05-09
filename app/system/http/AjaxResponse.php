<?php
namespace TODONS\system\http;
use Phalcon\Http\Response;

/**
 * This class is used to easily create a ajax response in form of a json string
 */
class AjaxResponse extends Response {

    /**
     * Initialize new ajax response
     *
     * @param mixed $data
     * @param bool $success
     * @param string $error
     */
    public function __construct($success = true, $data = null, $error = null) {
		$jsonData = [
			'success' => $success,
			'data' => $data,
			'error' => $error
		];

		parent::__construct(json_encode($jsonData), 200, 'OK');

        $this->setContentType('application/json', 'utf-8');
        $this->setHeader('Access-Control-Allow-Origin', empty($_SERVER['HTTP_ORIGIN']) ? '' : $_SERVER['HTTP_ORIGIN']);
        $this->setHeader('Access-Control-Allow-Credentials', 'true');
        $this->setHeader('Access-Control-Allow-Methods', 'POST, GET, OPTIONS');
        $this->setHeader('Access-Control-Allow-Headers', 'DNT,X-Mx-ReqToken,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type');
	}

	/**
	 * Set content which will be converted to json
	 *
	 * @param mixed $content
	 * @return \Phalcon\Http\ResponseInterface
	 */
	public function setContent($content) {
		$this->_content = $content;
		return $this;
	}
}
