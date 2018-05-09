<?php
namespace TODONS\factories;
use Phalcon\Security\Exception;
use TODONS\models\User;
use Phalcon\Di;
use Phalcon\Security\Random;
use TODONS\system\exception\SystemException;

class UserFactory {

    /**
     * @param string $email
     * @param string $password
     * @param string $nickname
     * @return User
     * @throws SystemException
     */
    public static function create(string $email, string $password, string $nickname): User {
        try {
            $random = new Random();
            $uid = $random->uuid();
        }
        catch (Exception $e) {
            throw new SystemException("Could not create uid: " . $e->getMessage());
        }

        $user = new User();
        $user->email = $email;
        $user->password = Di::getDefault()->get('security')->hash($password);
        $user->nickname = $nickname;
        $user->token = $uid;
        $user->created = NOW;

        return $user;
    }
}