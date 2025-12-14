<?php

namespace app\modules\user\controllers;

use app\components\RoleAccessRule;
use app\controllers\BaseController;
use app\models\User;
use app\modules\user\models\Role;
use app\modules\user\repositories\UserRepository;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Response;

/**
 * Default controller for the `user` module
 */
class DefaultController extends BaseController
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
                // 'only' => ['create', 'update', 'delete'], // restrict all
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => RoleAccessRule::class,
                ],
                'rules' => [
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
        $users   = User::find()->all();
        $newUser = new User();

        $usersArray = ArrayHelper::toArray($users, [
            User::class => array_diff(array_keys($newUser->attributes), [
                User::ATTRIBUTE_AUTH_KEY,
                User::ATTRIBUTE_PASSWORD,
            ]),
        ]);

        $usersById = [];
        foreach ($usersArray as $user) {
            $usersById[$user['id']] = $user;
        }

        return $this->render('index', [
            'newModel' => $newUser,
            'rows' => ArrayHelper::index($users, 'id'), // for table rendering
            'exclude' => [
                User::ATTRIBUTE_AUTH_KEY,
                User::ATTRIBUTE_PASSWORD,
            ],
            'title' => 'Users List',
            'modelJson' => $usersById,
            'route' => 'user',
            'isActionsDisplayed' => $this->canChangeData(),
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
