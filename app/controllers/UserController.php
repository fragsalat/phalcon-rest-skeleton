<?php
namespace TODONS\controller;
use TODONS\models\User;
use TODONS\system\exception\SystemException;
use TODONS\system\exception\ValidationException;
use TODONS\system\http\AjaxResponse;
use TODONS\system\validation\AuthenticateUserValidation;
use TODONS\system\validation\RegisterUserValidation;

class UserController extends ControllerBase {

    /**
     * Register a new user
     *
     * @return AjaxResponse
     * @throws ValidationException
     * @throws \TODONS\system\exception\SystemException
     * @throws \TODONS\system\exception\PermissionDeniedException
     * @throws \phpmailerException
     * @authenticated(false)
     */
    public function registerAction() {
        $data = $this->request->getJsonRawBody(true);
        // Validate the post data and throw an exception with it's messages
        $validation = new RegisterUserValidation();
        $errors = $validation->validate($data);
        // Check if we have some validation errors
        if (count($errors)) {
            $errors->rewind();
            throw new ValidationException($errors->current());
        }
        // Create the user entry
        $user = $this->userService->create($data);
        // Send activation mail
        try {
            $this->userService->sendActivationMail($user);
        }
        catch (SystemException $e) { }
        // Create token by authentication
        $this->authService->authenticate($data['email'], $data['password']);
        // Send back the new user
        return new AjaxResponse(true, ['token' => $this->authService->getToken()]);
    }

    /**
     * Authenticate a existing user
     *
     * @return AjaxResponse
     * @throws ValidationException
     * @throws \TODONS\system\exception\PermissionDeniedException
     * @authenticated(false)
     */
    public function authenticateAction() {
        $data = $this->request->getJsonRawBody(true);
        // Make minimum validation
        $validation = new AuthenticateUserValidation();
        $errors = $validation->validate($data);
        // Check if we have some validation errors
        if (count($errors)) {
            $errors->rewind();
            throw new ValidationException($errors->current());
        }
        // Authenticate the user
        if ($this->authService->authenticate($data['email'], $data['password'])) {
            return new AjaxResponse(true, ['token' => $this->authService->getToken()]);
        }

        return new AjaxResponse(false);
    }

    /**
     * Activates a user account
     *
     * @param string $token
     * @return AjaxResponse
     */
    public function activateAction(string $token) {
        if (!$this->authService->activate($token)) {
            return new AjaxResponse(false, null, 'Couldn\'t activate');
        }
        return new AjaxResponse(true);
    }
}
