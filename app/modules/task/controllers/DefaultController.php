<?php

namespace app\modules\task\controllers;

use app\controllers\BaseController;
use app\models\User;
use app\modules\constructionSite\models\ConstructionSite;
use app\modules\task\models\Task;
use app\modules\task\repositories\TaskRepository;
use app\modules\user\models\Role;
use yii\helpers\ArrayHelper;

class DefaultController extends BaseController
{
    public function init(): void
    {
        $this->repository = new TaskRepository();
        parent::init();
    }

    protected function getRules(): array
    {
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

    public function actionIndex(): string
    {
        $rows = ArrayHelper::index(Task::find()->all(), 'id');
        return $this->render('index', [
            'newModel' => new Task(),
            'rows' => ArrayHelper::index($rows, 'id'), // for table rendering
            'exclude' => [],
            'title' => 'Task List',
            'modelJson' => $rows,
            'route' => 'task',
            'isActionsDisplayed' => $this->canChangeData(),
            'users' => ArrayHelper::index(User::find()->all(), 'id'),
            'constructionSites' => ArrayHelper::index(ConstructionSite::find()->all(), 'id'),
        ]);
    }
}
