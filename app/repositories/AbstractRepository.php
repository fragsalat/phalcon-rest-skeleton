<?php
namespace TODONS\repositories;
use Phalcon\Di\Injectable;
use Phalcon\Mvc\Model;
use TODONS\system\exception\SystemException;

class AbstractRepository extends Injectable {

    /**
     * @param Model $model
     * @return Model
     * @throws SystemException
     */
    public function saveModel(Model $model): Model {
        if (!$model->save()) {
            // Throw an exception with reported error messages
            $error = array_reduce($model->getMessages(), function($message, $joined) {
                return $joined . (!empty($message) ? ' | ' . $message : '');
            });
            throw new SystemException('Error on saving model: ' . $error);
        }

        return $model;
    }
}