<?php

namespace app\modules\user\controllers;

use app\modules\user\models\User;
use app\modules\user\repositories\UserRepository;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;

/**
 * Default controller for the `user` module
 */
class DefaultController extends Controller
{

    public function __construct(
        $id,
        $module,
        private readonly UserRepository $userRepository,
        $config = [],
    ) {
        parent::__construct($id, $module, $config);
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
                'class' => AccessControl::class,
                // 'only' => ['create', 'update', 'delete'], // restrict all
                'rules' => [
                    [
                        'actions' => ['create', 'update'],
                        'allow' => true,
                        'roles' => ['@'], // logged-in users
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['admin'], // only admin role
                    ],
                ],
            ],
        ];
    }

    /**
     * @noinspection PhpUnused
     * /user
     */
    public function actionIndex(): string
    {
        return $this->render('index', [
            'newUser' => new User(),
            'users' => ArrayHelper::index(User::find()->all(), 'id')
        ]);
    }

    /**
     * @noinspection PhpUnused
     * /user/create
     */
    public function actionCreate(): Response|string
    {
        $postData = Yii::$app->request->post();

        if (!isset($postData['User'])) {
            Yii::$app->session->setFlash('error', 'No user data submitted.');
            return $this->redirect(['index']);
        }

        try {
            $this->userRepository->create($postData);
            Yii::$app->session->setFlash('success', 'User created successfully.');
        } catch (\Throwable $e) {
            Yii::$app->session->setFlash('error', 'Failed to create user: ' . $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    /**
     * @noinspection PhpUnused
     * /user/delete
     */
    public function actionDelete(int $id): Response
    {
        try {
            $this->userRepository->delete($id);
            Yii::$app->session->setFlash('success', 'User deleted.');
        } catch (\Throwable $e) {
            Yii::$app->session->setFlash('error', 'Failed to delete user: ' . $e->getMessage());
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
            $user = $this->userRepository->update($id, Yii::$app->request->post());
            Yii::$app->session->setFlash(
                'success',
                'User updated successfully.' . $user->first_name . $user->last_name
            );
        } catch (\Throwable $e) {
            Yii::$app->session->setFlash('error', 'Failed to update user: ' . $e->getMessage());
        }

        return $this->redirect(['index']);
    }

}
