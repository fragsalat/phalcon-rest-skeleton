<?php
namespace TODONS\services;
use Firebase\JWT\JWT;
use TODONS\models\User;
use TODONS\system\exception\PermissionDeniedException;
use Phalcon\Di\Injectable;

/**
 * This class handles request authorization
 *
 * @package TODONS\services
 */
class AuthService extends Injectable {

	/**
	 * @var string
	 */
	private $token = null;

	/**
	 * @var object
	 */
	private $payload = null;

	/**
	 * @var string
	 */
	private $secretKey;

	/**
	 * @var string
	 */
	private $algorithm;

	/**
	 * @var int
	 */
	private $timeout;

	/**
	 * @param $secretKey
	 * @param $algorithm
	 * @param int $timeout
	 */
	public function __construct($secretKey, $algorithm, $timeout = 3600) {
		$this->secretKey = $secretKey;
		$this->algorithm = $algorithm;
		$this->timeout = $timeout;
	}

	/**
	 * Check if the session is authenticated
	 *
	 * @return bool
	 * @throws PermissionDeniedException
	 */
	public function isAuthenticated() {
		$payload = $this->getPayload();
		// If there is no token the session isn't authenticated
		if (empty($payload)) {
			return false;
		}
		// The token must come from this domain
		if ($payload->iss !== $_SERVER['SERVER_NAME']) {
			return false;
		}
		// Check if token is expired
		if ($payload->exp < NOW) {
			// Create a new token if the session should persist
			if (!empty($payload->persist)) {
				$this->createToken($payload->user);
				return true;
			}
			else {
				return false;
			}
		}
		// If the token isn't expired the token was created within last $this->timeout seconds
		if ($payload->iat < NOW - $this->timeout) {
			return false;
		}
		// Validate the user data
		if (empty($payload->user) || empty($payload->user->id)) {
			return false;
		}

		return true;
	}

	/**
	 * Authenticate a user by it's email and password and create a token for this session
	 *
	 * @param string $login
	 * @param string $password
	 * @return bool
	 * @throws PermissionDeniedException
	 */
	public function authenticate($login, $password) {
		$user = $this->userRepository->getByMail($login);
		// Check if we have a user with these email
		if (empty($user)) {
			throw new PermissionDeniedException();
		}
		// Compare passwords
		if (!$this->security->checkHash($password, $user->password)) {
			throw new PermissionDeniedException();
		}
		// Create new Token for current user
		$this->createToken((object)[
			'id' => $user->id,
			'email' => $user->email,
			'nickname' => $user->nickname,
			'activated' => empty($user->token),
			'created' => $user->created
		]);

		return true;
	}

	/**
	 * Create a new payload and token with given data
	 *
	 * @param object $user
	 */
	private function createToken($user) {
		// Create new payload
		$this->payload = (object)[
			'iat' => NOW,
			'exp' => NOW + $this->timeout,
			'iss' => $_SERVER['SERVER_NAME'],
			'user' => $user
		];
		// Encode new payload

		$this->token = JWT::encode($this->payload, $this->secretKey, $this->algorithm);
	}

	/**
	 * Get token of current session
	 *
	 * @return null|string
	 */
	public function getToken() {
		// Check if we have to determine the token
		if (empty($this->token)) {
			$bearer = $this->request->getHeader('Authorization');
			// Parse token from header value
			if (sscanf($bearer, 'Bearer %s', $encodedToken)) {
				$this->token = $encodedToken;
			}
		}
		// Maybe we should throw a exception here?
		return $this->token;
	}

	/**
	 * Get payload of token for current session
	 *
	 * @return null|object
	 * @throws PermissionDeniedException
	 */
	public function getPayload() {
		// Check if we have to parse a token and if the token is available
		if (empty($this->payload) && !empty($this->getToken())) {
			try {
				// Decode payload and validate token
				$this->payload = JWT::decode($this->getToken(), $this->secretKey, [$this->algorithm]);
			}
			catch (\Exception $e) {
				throw new PermissionDeniedException($e);
			}
		}

		return $this->payload;
	}
}