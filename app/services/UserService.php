<?php
namespace TODONS\services;
use TODONS\factories\UserFactory;
use TODONS\models\User;
use TODONS\system\exception\SystemException;
use Phalcon\Di\Injectable;

/**
 * Service to handle user models
 *
 * @package TODONS\service
 * @property \TODONS\repositories\UserRepository $userRepository
 * @property \PHPMailer $mailer
 */
class UserService extends Injectable {

    /**
     * Create a new user
     *
     * @param array $data
     * @return User
     * @throws SystemException
     */
	public function create(array $data): User {
		// Create the user entry
		$user = UserFactory::create($data['email'], $data['password'], $data['nickname']);
		// Store the entry in database
		return $this->userRepository->save($user);
	}

    /**
     * @param User $user
     * @return bool
     * @throws SystemException
     * @throws \phpmailerException
     */
	public function sendActivationMail(User $user) {
	    try {
            $html = $this->view->render('mail/activation.html', ['user' => $user]);
            $text = $this->view->render('mail/activation.text', ['user' => $user]);

            $this->mailer->AddAddress($user->email, $user->nickname);
            $this->mailer->isHTML(true);
            $this->mailer->SMTPDebug = 0;
            $this->mailer->Subject = 'Please activate your account';
            $this->mailer->Body = $html;
            $this->mailer->AltBody = $text;

            $response = $this->mailer->send();
        }
        finally {
            if (!$response) {
                throw new SystemException('Activation mail couldn\'t be send');
            }
        }

		return true;
	}

    /**
     * Activates a user account
     *
     * @param string $token
     * @return bool
     * @throws SystemException
     */
	public function activate(string $token): bool {
	    $user = User::findFirst([
	        'token = ?0',
            'bind' => [$token]
        ]);

	    if (empty($user)) {
	        return false;
        }

        $user->token = null;
	    $user = $this->userRepository->save($user);

	    return !empty($user);
    }
}