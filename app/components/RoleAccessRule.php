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
    protected function matchRole( $user): bool
    {
        //todo extend
        if (empty($this->roles)) {
            return true;
        }

        foreach ($this->roles as $role) {
            if (!$user->isGuest && $user->identity->hasRole($role)) {
                return true;
            }
        }
        return false;
    }
}
