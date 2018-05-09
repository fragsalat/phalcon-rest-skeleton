<?php
namespace TODONS\system\validation\validator;
use Phalcon\Validation;
use Phalcon\Validation\Message;
use Phalcon\Validation\Validator;
use Phalcon\Validation\ValidatorInterface;

class TagValidator extends Validator implements ValidatorInterface {

	/**
	 * Executes the validation
	 *
	 * @param mixed $validation
	 * @param string $attribute
	 * @return bool
	 */
	public function validate(Validation $validation, $attribute) {
		$value = $validation->getValue($attribute);
		$message = $this->getOption('message') ?: 'The tag is invalid';
		// Check if the value is empty or is not an array or doesn't contain id or name
		if (empty($value) || !is_array($value) || (empty($value['id']) && empty($value['name']))) {
			$validation->appendMessage(new Message($message, $attribute, 'array'));
		}

		return true;
	}
}