<?php
namespace TODONS\system\validation;
use Phalcon\Validation;

/**
 * Validate POST data which is sent to register a new user
 *
 * @package TODONS\system\validation
 */
class AuthenticateUserValidation {

	/**
	 * @var Validation
	 */
	public $validation;

	/**
	 * Setup validation strategy
	 *
	 * @param Validation $validation
	 */
	public function __construct(Validation $validation = null) {
		// Setup validation for this request
		$this->validation = $validation ?: new Validation();
		$this->validation->add('email', new Validation\Validator\Email([
			'message' => 'The E-Mail is not valid'
		]));
		$this->validation->add('password', new Validation\Validator\Regex([
			'pattern' => '~^[a-zA-Z0-9!_\-+\$]+$~',
			'message' => 'The password can only contain a-zA-Z0-9!_-+$'
		]));
	}

	/**
	 * Validate data
	 *
	 * @param array $data
	 * @return Validation\Message\Group
	 */
	public function validate($data) {
		return $this->validation->validate($data);
	}
}