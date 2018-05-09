<?php
namespace TODONS\system\validation\validator;
use Phalcon\Validation;
use Phalcon\Validation\Message;
use Phalcon\Validation\Validator;
use Phalcon\Validation\ValidatorInterface;

/**
 * This class aims to apply a validator to a list of values
 *
 * Usage: new ArrayValidator([
 *     'message' => 'The given value is not a list',
 *     'validator' => new Email(['message' => 'Invalid email'])
 * ])
 *
 * @package TODONS\system\validation\validator
 */
class ArrayValidator extends Validator implements ValidatorInterface {

	/**
	 * Executes the validation
	 *
	 * @param mixed $validation
	 * @param string $attribute
	 * @return bool
	 */
	public function validate(Validation $validation, $attribute) {
		$list = $validation->getValue($attribute);
		$validator = $this->getOption('validator');
		$message = $this->getOption('message') ?: 'The given value is not a list';
		// Check if the value is empty or is not an array or doesn't contain id or name
		if (empty($list) || !is_array($list)) {
			$validation->appendMessage(new Message($message, $attribute, 'array'));
			return false;
		}
		// Add validator for each list entry
		$listValidation = new Validation();
		foreach ($list as $index => $value) {
			$listValidation->add($index, $validator);
		}
		// Validate the list
		$messages = $listValidation->validate($list);
		if (count($messages)) {
			// Add error messages to parent validation
			foreach ($messages as $message) {
				$message->setField($attribute . '[' . $message->getField() . ']');
				$validation->appendMessage($message);
			}
			return false;
		}

		return true;
	}
}