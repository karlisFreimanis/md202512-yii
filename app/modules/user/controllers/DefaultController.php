<?php

namespace app\modules\user\controllers;

use app\controllers\BaseController;
use app\models\User;
use app\modules\user\models\Role;
use app\modules\user\repositories\UserRepository;
use yii\helpers\ArrayHelper;

/**
 * Default controller for the `user` module
 */
class DefaultController extends BaseController
{
    public function init(): void
    {
        $this->repository = new UserRepository();
        parent::init();
    }

    protected function getRules() :array {
        return [
            [
                'actions' => ['create', 'update', 'delete'],
                'allow' => true,
                'roles' => [Role::ROLE_ADMIN],
            ],
            [
                'actions' => ['index'],
                'allow' => true,
                'roles' => Role::ROLE_ALL,
            ],
        ];
    }

    /**
     * @noinspection PhpUnused
     * /user
     */
    public function actionIndex(): string
    {
        $users   = User::find()->all();
        $newUser = new User();
        $rows = ArrayHelper::index(
            $users,
            'id'
        );

        return $this->render('index', [
            'newModel' => $newUser,
            'rows' => ArrayHelper::index($users, 'id'), // for table rendering
            'exclude' => [
                User::ATTRIBUTE_AUTH_KEY,
                User::ATTRIBUTE_PASSWORD,
            ],
            'title' => 'Users List',
            'modelJson' => $rows,
            'users' => $rows,
            'route' => 'user',
            'isActionsDisplayed' => $this->canChangeData(),
            'roles' => ArrayHelper::index(Role::find()->all(), 'id'),
        ]);
    }
}
