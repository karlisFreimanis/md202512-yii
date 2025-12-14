<?php

namespace app\controllers;

use app\components\RoleAccessRule;
use Yii;
use yii\web\Controller;

abstract class BaseController extends Controller
{
    protected function canChangeData(): bool
    {
        $adminActions     = ['create', 'update', 'delete'];
        $currentUserRoles = array_column(Yii::$app->user->identity->getRoles()->asArray()->all(), 'name');
        $access           = $this->getBehavior('access');
        if (!$access) {
            return false;
        }

        foreach ($access->rules as $rule) {
            /** @var RoleAccessRule $rule */
            if (array_intersect($rule->actions, $adminActions)) {
                if (!empty(array_intersect($currentUserRoles, $rule->roles))) {
                    return true;
                }
            }
        }

        return false;
    }
}