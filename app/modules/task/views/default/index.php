<?php

use yii\bootstrap5\Html;

/* @var yii\web\View $this */
/* @var string $title */
/* @var yii\db\ActiveRecord[] $rows keyed by ID */
/* @var yii\db\ActiveRecord $newModel */
/* @var string $modelJson */

$this->title = $title;
?>

    <h1><?= Html::encode($this->title) ?></h1>

<?= $this->render('@app/views/layouts/_table.php', [
        'rows' => $rows,
        'exclude' => $exclude ?? [],
        'formatters' => [
                'user_id' => fn($task) => $task->user_id
                        ? $users[$task->user_id]->first_name . ' ' . $users[$task->user_id]->last_name
                        : '-',
                'construction_site_id' => fn($task) => $task->construction_site_id
                        ? $constructionSites[$task->construction_site_id]->address
                        : '-',
        ],
        'newModel' => $newModel,
        'modelJson' => $modelJson,
        'route' => $route,
        'isActionsDisplayed' => $isActionsDisplayed,
        'users' => $users,
]) ?>