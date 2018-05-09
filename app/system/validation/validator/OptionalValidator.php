<?php
namespace TODONS\system\validation\validator;
use Phalcon\Validation;
use Phalcon\Validation\Message;
use Phalcon\Validation\Validator;
use Phalcon\Validation\ValidatorInterface;

/**
 * This class aims to apply a validator to a value only if there is a value
 *
 * Usage: new OptionalValidator([
 *     'validator' => new Email(['message' => 'Invalid email'])
 * ])
 *
 * @package TODONS\system\validation\validator
 */
class OptionalValidator extends Validator implements ValidatorInterface {

	/**
	 * Executes the validation
	 *
	 * @param mixed $validation
	 * @param string $attribute
	 * @return bool
	 */
	public function validate(Validation $validation, $attribute) {
		$value = $validation->getValue($attribute);
		$validator = $this->getOption('validator');

		if (!is_null($value)) {
			return $validator->validate($validation, $attribute);
		}

		return true;
	}
}