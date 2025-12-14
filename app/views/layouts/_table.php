<?php

use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;
use \yii\helpers\Json;

$model      = reset($rows);
$exclude    = $exclude ?? [];
$attributes = array_diff(array_keys($model->attributes), $exclude);
$labels     = $model->attributeLabels();
$users      = $users ?? [];

?>

<?php
if (!empty($isActionsDisplayed)): ?>
    <div class="mb-3">
        <?= Html::button('Create Record', [
            'class' => 'btn btn-success',
            'data-bs-toggle' => 'modal',
            'data-bs-target' => '#recordModal',
            'id' => 'create-record-btn',
        ]) ?>
    </div>
<?php
endif; ?>

<table class="table table-bordered table-striped table-hover">
    <thead class="table-dark">
    <tr>
        <?php
        if (!empty($isActionsDisplayed)): ?>
            <th>Actions</th>
        <?php
        endif; ?>
        <?php
        foreach ($attributes as $attr): ?>
            <th><?= Html::encode($labels[$attr] ?? $attr) ?></th>
        <?php
        endforeach; ?>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($rows as $row): ?>
        <tr>
            <?php
            if (!empty($isActionsDisplayed)): ?>
                <td class="text-center">
                    <?= Html::a(
                        '<i class="bi bi-pencil"></i>',
                        'javascript:void(0)',
                        [
                            'class' => 'edit-record-btn ms-2', // match JS
                            'title' => 'Edit',
                            'data-id' => $row->id,
                            'data-bs-toggle' => 'modal',
                            'data-bs-target' => '#recordModal', // match modal ID
                        ]
                    ) ?>
                    <?= Html::a('<i class="bi bi-trash"></i>', ['/' . $route . '/delete', 'id' => $row->id], [
                        'title' => 'Delete',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete?',
                            'method' => 'post',
                        ],
                        'class' => 'ms-2 text-danger'
                    ]) ?>
                </td>
            <?php
            endif; ?>
            <?php
            foreach ($attributes as $attr): ?>
                <td>
                    <?= Html::encode(
                        isset($formatters[$attr])
                            ? $formatters[$attr]($row)
                            : $row->$attr
                    ) ?>
                </td>
            <?php
            endforeach; ?>
        </tr>
    <?php
    endforeach; ?>
    </tbody>
</table>

<!-- UNIVERSAL MODAL FORM -->
<div class="modal fade" id="recordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php
            $form = \yii\widgets\ActiveForm::begin([
                'id' => 'record-form',
                'enableClientValidation' => true,
            ]); ?>

            <div class="modal-header">
                <h5 class="modal-title" id="recordModalTitle">Create Record</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <?php
                $attributes = $newModel->attributes();
                $exclude    = ['id', 'created_at', 'updated_at', 'auth_key'];

                foreach ($attributes as $attr) {
                    if (in_array($attr, $exclude)) {
                        continue;
                    }

                    $inputId = 'record-' . $attr; // important for JS

                    if ($attr === 'password') {
                        echo $form->field($newModel, $attr)->passwordInput(['id' => $inputId]);
                    } elseif (str_contains($attr, 'date') || $attr === 'birthday') {
                        echo $form->field($newModel, $attr)->input('date', ['id' => $inputId]);
                    } elseif (in_array($attr, ['user_id', 'manager_id'])) {
                        // Foreign key dropdown
                        echo $form->field($newModel, $attr)->dropDownList(
                                ArrayHelper::map($users, 'id', fn($row) => $row->first_name . ' ' . $row->last_name),
                                ['prompt' => '— none —', 'id' => $inputId]
                        );
                    } elseif ($attr === 'role_id') {
                        // Foreign key dropdown
                        echo $form->field($newModel, $attr)->dropDownList(
                                ArrayHelper::map($roles, 'id', fn($row) => $row->name),
                                ['prompt' => '— none —', 'id' => $inputId]
                        );
                    } else {
                        echo $form->field($newModel, $attr)->textInput(['id' => $inputId]);
                    }
                }
                ?>
            </div>

            <div class="modal-footer">
                <?= Html::submitButton('Save', [
                    'class' => 'btn btn-success',
                    'id' => 'record-submit-btn',
                ]) ?>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>

            <?php
            \yii\widgets\ActiveForm::end(); ?>
        </div>
    </div>
</div>

<script>
    const records = <?= Json::htmlEncode($modelJson) ?>; // correctly encodes PHP array to JS

    // CREATE
    $('#create-record-btn').on('click', () => {
        $('#recordModalTitle').text('Create Record');
        $('#record-form').attr('action', '/<?= $route ?>/create');
        $('#record-form')[0].reset();
    });

    $(document).on('click', '.edit-record-btn', function () {
        const recordId = $(this).data('id');
        const record = records[recordId];
        if (!record) return;

        $('#recordModalTitle').text('Update Record');
        $('#record-form').attr('action', '/<?= $route ?>/update?id=' + recordId);

        Object.keys(record).forEach(attr => {
            const input = $('#record-' + attr);
            if (!input.length) return;

            if (attr === 'birthday' && record[attr]) {
                input.val(record[attr].split(' ')[0]); // YYYY-MM-DD
            } else {
                input.val(record[attr]);
            }
        });
    });
</script>