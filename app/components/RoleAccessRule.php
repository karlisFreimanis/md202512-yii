<?php
namespace app\components;

use app\models\User;
use yii\filters\AccessRule;

class RoleAccessRule extends AccessRule
{
    /**
     * @param User $user
     * @return bool
     */
    protected function matchRole($user): bool
    {
        if ($user->isGuest) {
            return false;
        }

        return in_array($user->identity->role->name, $this->roles);
    }
}
