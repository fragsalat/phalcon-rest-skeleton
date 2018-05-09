<?php
namespace TODONS\repositories;
use Phalcon\Mvc\Model;
use TODONS\system\exception\SystemException;
use TODONS\models\User;
use TODONS\models\Follower;

/**
 * Class UserRepository
 * @package TODONS\repositories
 * @method User|Follower saveModel(Model $model)
 */
class UserRepository extends AbstractRepository {

    /**
     * @param int $userId
     * @return User
     */
    public function get(int $userId): ?User {
        $user = User::findFirst($userId);

        return $user ?: null;
    }

    /**
     * @param string $email
     * @return User
     */
    public function getByMail(string $email): ?User {
        $user = User::findFirst([
			'conditions' => 'email LIKE ?0',
			'bind' => [$email]
		]);

        return $user ?: null;
    }

    /**
     * @param User $user
     * @return User
     * @throws SystemException
     */
    public function save(User $user): User {
        return $this->saveModel($user);
    }
}