<?php

namespace app\modules\constructionSite\controllers;

use app\modules\constructionSite\models\ConstructionSite;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

/**
 * Default controller for the `constructionSite` module
 */
class DefaultController extends Controller
{
    public function actionIndex(): string
    {
        $constructionSites   = ConstructionSite::find()->all();

        $modelsById = [];
        foreach (ArrayHelper::toArray($constructionSites) as $constructionSite) {
            $modelsById[$constructionSite['id']] = $constructionSite;
        }

        return $this->render('index', [
            'newModel' => new ConstructionSite(),
            'rows' => ArrayHelper::index($constructionSites, 'id'), // for table rendering
            'exclude' => [],
            'title' => 'Construction Site List',
            'modelJson' => json_encode($modelsById),
            'route' => 'constructionSite',
        ]);
    }
}
