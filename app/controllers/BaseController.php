<?php

namespace app\controllers;

use app\components\RoleAccessRule;
use app\repositories\BaseRepository;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

abstract class BaseController extends Controller
{
    protected string $objectName = 'Object';
    protected BaseRepository $repository;

    public function init(): void
    {
        parent::init();
        $this->objectName = $this->repository()->getName();
    }

    protected function repository(): BaseRepository
    {
        if (!isset($this->repository)) {
            throw new \RuntimeException('Repository is not initialized in this controller.');
        }
        return $this->repository;
    }

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'create' => ['POST'],
                    'update' => ['POST'],
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                // 'only' => ['create', 'update', 'delete'], // restrict all
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => RoleAccessRule::class,
                ],
                'rules' => $this->getRules(),
            ],
        ];
    }

    abstract protected function getRules(): array;

    protected function canChangeData(): bool
    {
        $adminActions = ['create', 'update', 'delete'];
        $access       = $this->getBehavior('access');
        if (!$access) {
            return false;
        }

        foreach ($access->rules as $rule) {
            /** @var RoleAccessRule $rule */
            if (array_intersect($rule->actions, $adminActions)) {
                if (in_array(Yii::$app->user->identity->role->name, $rule->roles)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @noinspection PhpUnused
     * /user/delete
     */
    public function actionDelete(int $id): Response
    {
        try {
            $this->repository->delete($id);
            Yii::$app->session->setFlash('success', $this->objectName. ' deleted.');
        } catch (\Throwable $e) {
            Yii::$app->session->setFlash(
                'error',
                sprintf('Failed to delete %s : %s', $this->objectName, $e->getMessage()),
            );
        }

        return $this->redirect(['index']);
    }

    /**
     * @noinspection PhpUnused
     * /user/update
     */
    public function actionUpdate(int $id): Response
    {
        try {
            $model = $this->repository->update($id, Yii::$app->request->post());
            Yii::$app->session->setFlash(
                'success',
                $this->objectName . ' updated successfully.'
            );
        } catch (\Throwable $e) {
            Yii::$app->session->setFlash(
                'error',
                sprintf('Failed to update %s : %s', $this->objectName, $e->getMessage()),
            );
        }

        return $this->redirect(['index']);
    }

    /**
     * @noinspection PhpUnused
     * /user/create
     */
    public function actionCreate(): Response|string
    {
        $postData = Yii::$app->request->post();

        if (!isset($postData[$this->objectName])) {
            Yii::$app->session->setFlash('error', sprintf('No %s data submitted.', $this->objectName));
            return $this->redirect(['index']);
        }

        try {
            $this->repository->create($postData);
            Yii::$app->session->setFlash('success', $this->objectName . ' created successfully.');
        } catch (\Throwable $e) {
            Yii::$app->session->setFlash(
                'error',
                sprintf('Failed to create %s : %s', $this->objectName, $e->getMessage()),
            );
        }

        return $this->redirect(['index']);
    }
}