<?php

declare(strict_types=1);

namespace app\modules\user\repositories;

use app\models\User;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

class UserRepository
{
    /**
     * @param array $postData
     * @return User
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public function create(array $postData): User
    {
        $user           = new User();
        $user->scenario = User::SCENARIO_CREATE;
        $user->load($postData);
        $user->hashPassword()
            ->hashAuthKey();

        if (!$user->save()) {
            throw new Exception(implode('; ', ArrayHelper::getColumn($user->errors, 0)));
        }

        return $user;
    }

    /**
     * @param int $id
     * @return bool
     * @throws \yii\db\Exception
     */
    public function delete(int $id): bool
    {
        $user = User::findOne(['id' => $id]);
        try {
            return (bool)$user->delete();
        } catch (\Throwable $e) {
            throw new \yii\db\Exception($e->getMessage());
        }
    }

    /**
     * @param int $id
     * @param array $postData
     * @return User
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public function update(int $id, array $postData): User {
        $user = User::findOne(['id' => $id]);

        $user->scenario = User::SCENARIO_UPDATE;
        $user->load($postData);

        if (!$user->save()) {
            throw new Exception('User update failed: ' . json_encode($user->errors));
        }

        return $user;
    }
}
