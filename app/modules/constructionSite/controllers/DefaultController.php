<?php

namespace app\modules\constructionSite\controllers;

use app\controllers\BaseController;
use app\modules\constructionSite\models\ConstructionSite;
use app\modules\constructionSite\repositories\ConstructionSiteRepository;
use app\modules\user\models\Role;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Default controller for the `constructionSite` module
 */
class DefaultController extends BaseController
{
    public function init(): void
    {
        $this->repository = new ConstructionSiteRepository();
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
        /** @var ConstructionSiteRepository $repository */
        $repository        = $this->repository();
        $constructionSites = $repository->getUserConstructionSites(Yii::$app->user->identity);
        $modelsById        = [];
        foreach (ArrayHelper::toArray($constructionSites) as $constructionSite) {
            $modelsById[$constructionSite['id']] = $constructionSite;
        }

        return $this->render('index', [
            'newModel' => new ConstructionSite(),
            'rows' => ArrayHelper::index($constructionSites, 'id'), // for table rendering
            'exclude' => [],
            'title' => 'Construction Site List',
            'modelJson' => $modelsById,
            'route' => 'constructionSite',
            'isActionsDisplayed' => $this->canChangeData(),
        ]);
    }
}
