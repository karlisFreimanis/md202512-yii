<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\web\View;

/* @var View $this */
/* @var array $users keyed by ID */
/* @var app\models\User $newUser */

$this->title = 'Users List';
$usersById = $users;

// Prepare data for JS
$usersJson = json_encode(
        ArrayHelper::toArray($usersById, [
                app\models\User::class => [
                        'id',
                        'username',
                        'first_name',
                        'last_name',
                        'manager_id',
                        'access_level',
                        'birthday',
                ],
        ])
);
?>

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="mb-3">
        <?= Html::button('Create User', [
                'class' => 'btn btn-success',
                'data-bs-toggle' => 'modal',
                'data-bs-target' => '#userModal',
                'id' => 'create-user-btn',
        ]) ?>
    </div>

    <table class="table table-bordered table-striped table-hover">
        <thead class="table-dark">
        <tr>
            <th>Actions</th>
            <th>ID</th>
            <th>Manager</th>
            <th>Username</th>
            <th>First name</th>
            <th>Last name</th>
            <th>Access</th>
            <th>Birthday</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($usersById as $user): ?>
            <tr>
                <td class="text-center">
                    <?= Html::a(
                            '<i class="bi bi-pencil"></i>',
                            'javascript:void(0)',
                            [
                                    'class' => 'edit-user-btn ms-2',
                                    'title' => 'Edit',
                                    'data-id' => $user->id,
                                    'data-bs-toggle' => 'modal',
                                    'data-bs-target' => '#userModal',
                            ]
                    ) ?>
                    <?= Html::a('<i class="bi bi-trash"></i>', ['/user/delete', 'id' => $user->id], [
                            'title' => 'Delete',
                            'data' => [
                                    'confirm' => 'Are you sure you want to delete this user?',
                                    'method' => 'post',
                            ],
                            'class' => 'ms-2 text-danger'
                    ]) ?>
                </td>

                <td><?= Html::encode($user->id) ?></td>
                <td>
                    <?= Html::encode(
                            $user->manager_id
                                    ? ($usersById[$user->manager_id]->first_name . ' ' . $usersById[$user->manager_id]->last_name)
                                    : '-'
                    ) ?>
                </td>
                <td><?= Html::encode($user->username) ?></td>
                <td><?= Html::encode($user->first_name) ?></td>
                <td><?= Html::encode($user->last_name) ?></td>
                <td><?= Html::encode($user->access_level) ?></td>
                <td><?= Html::encode($user->birthday) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- USER MODAL (CREATE + EDIT) -->
    <div class="modal fade" id="userModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <?php
                use yii\widgets\ActiveForm;
                $form = ActiveForm::begin([
                        'id' => 'user-form',
                        'enableClientValidation' => true,
                ]);
                ?>

                <div class="modal-header">
                    <h5 class="modal-title" id="userModalTitle">Create User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <?= $form->field($newUser, 'username')->textInput(['id' => 'user-username']) ?>
                    <?= $form->field($newUser, 'first_name')->textInput(['id' => 'user-first_name']) ?>
                    <?= $form->field($newUser, 'last_name')->textInput(['id' => 'user-last_name']) ?>

                    <?= $form->field($newUser, 'manager_id')->dropDownList(
                            ArrayHelper::map($usersById, 'id', fn($u) => $u->first_name . ' ' . $u->last_name),
                            ['prompt' => '— none —', 'id' => 'user-manager_id']
                    ) ?>

                    <?= $form->field($newUser, 'access_level')->input('number', ['id' => 'user-access_level']) ?>
                    <?= $form->field($newUser, 'birthday')->input('date', ['id' => 'user-birthday']) ?>
                    <?= $form->field($newUser, 'password')->passwordInput() ?>
                </div>

                <div class="modal-footer">
                    <?= Html::submitButton('Save', [
                            'class' => 'btn btn-success',
                            'id' => 'user-submit-btn',
                            'data-loading-text' => 'Saving...',
                    ]) ?>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

<?php
$this->registerJs("
const users = $usersJson;

// CREATE
$('#create-user-btn').on('click', () => {
    $('#userModalTitle').text('Create User');
    $('#user-form').attr('action', '/user/create');
    $('#user-form')[0].reset();
});

// EDIT
$('.edit-user-btn').on('click', function () {
    const user = users[$(this).data('id')];
    if (!user) return;

    $('#userModalTitle').text('Update User');
    $('#user-form').attr('action', '/user/update?id=' + user.id);

    $('#user-username').val(user.username);
    $('#user-first_name').val(user.first_name);
    $('#user-last_name').val(user.last_name);
    $('#user-manager_id').val(user.manager_id);
    $('#user-access_level').val(user.access_level);
    $('#user-birthday').val(user.birthday);
});
");
