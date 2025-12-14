<?php

declare(strict_types=1);

namespace app\modules\user\repositories;

use app\models\User;
use app\modules\user\models\Role;
use app\repositories\BaseRepository;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

class UserRepository extends BaseRepository
{
    protected string $modelClass = User::class;

    /**
     * @param array $data
     * @return User
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public function create(array $data): User
    {
        $user           = new User();
        $user->scenario = User::SCENARIO_CREATE;
        $user->load($data);
        $user->hashPassword()
            ->hashAuthKey();

        if (!$user->save()) {
            throw new Exception(implode('; ', ArrayHelper::getColumn($user->errors, 0)));
        }

        return $user;
    }

    /**
     * @param int $id
     * @param array $data
     * @return User
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public function update(int $id, array $data): User {
        $user = User::findOne(['id' => $id]);

        $user->scenario = User::SCENARIO_UPDATE;
        $user->load($data);

        if (!$user->save()) {
            throw new Exception('User update failed: ' . json_encode($user->errors));
        }

        return $user;
    }

    public function getLinkedUsers(User $user): array
    {
        return match ($user->role->name) {
            Role::ROLE_ADMIN => User::find()->all(),
            Role::ROLE_MANAGER => User::find()
                ->where(['manager_id' => $user->id])
                ->all(),
            Role::ROLE_EMPLOYEE => [$user],
            default => [],
        };
    }
}
