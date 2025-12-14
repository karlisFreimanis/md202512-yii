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

        $currentUserRoles = array_column($user->identity->getRole()->asArray()->all(), 'name');
        return (bool)array_intersect($this->roles, $currentUserRoles);
    }
}
