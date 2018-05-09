<?php
namespace TODONS\models;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Validator\Email;
use Phalcon\Mvc\Model\Validator\Uniqueness;

/**
 * Class User
 * @package TODONS\models
 * @method static User findFirst($parameters = null)
 */
class User extends Model {

    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $nickname;

    /**
     * @var string
     */
    public $token = 0;

    /**
     * @var integer
     */
    public $created;

    /**
     * @return array
     */
    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'nickname' => $this->nickname,
            'created' => $this->created
        ];
    }
}
