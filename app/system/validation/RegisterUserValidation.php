<?php
namespace TODONS\system\validation;
use Phalcon\Validation;
use TODONS\models\User;

/**
 * Validate POST data which is sent to register a new user
 *
 * @package TODONS\system\validation
 */
class RegisterUserValidation {

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
        $this->validation->add('nickname', new Validation\Validator\StringLength([
            'min' => 3,
            'max' => 30,
            'messageMinimum' => 'The nickname is to short',
            'messageMaximum' => 'The nickname is to long'
        ]));
        $this->validation->add('nickname', new Validation\Validator\Uniqueness([
            'message' => 'The nickname is already chosen',
            'model' => new User()
        ]));
        $this->validation->add('nickname', new Validation\Validator\Regex([
            'pattern' => '~^[a-zA-Z0-9_\-+\^]+$~',
            'message' => 'The nickname can only contain a-zA-Z0-9_-+^'
        ]));
		$this->validation->add('email', new Validation\Validator\Email([
			'message' => 'The E-Mail is not valid'
		]));
		$this->validation->add('email', new Validation\Validator\Uniqueness([
		    'message' => 'The E-Mail is already chosen',
            'model' => new User()
        ]));
		$this->validation->add('password', new Validation\Validator\Regex([
			'pattern' => '~^[a-zA-Z0-9!_\-+\$]+$~',
			'message' => 'The password can only contain a-zA-Z0-9!_-+$'
		]));
		$this->validation->add('password', new Validation\Validator\Confirmation([
			'with' => 'confirmPassword',
			'message' => 'The passwords doesn\'t match'
		]));
		$this->validation->add('password', new Validation\Validator\StringLength([
			'min' => 5,
			'max' => 32,
			'messageMinimum' => 'The password is to short',
			'messageMaximum' => 'The password is to long'
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