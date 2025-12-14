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
                'manager_id' => fn($user) => $user->manager_id
                        ? $rows[$user->manager_id]->first_name . ' ' . $rows[$user->manager_id]->last_name
                        : '-',
                'birthday' => fn($user) => $user->birthday ?: '—',
                'role_id' => fn($user) => $user->role_id
                        ? $roles[$user->role_id]->name
                        : '-',
        ],
        'newModel' => $newModel,
        'modelJson' => $modelJson,
        'route' => $route,
        'isActionsDisplayed' => $isActionsDisplayed,
        'users' => $users,
        'roles' => $roles,
]) ?>