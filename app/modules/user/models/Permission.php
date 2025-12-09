<?php

namespace app\modules\user\models;


use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "permissions".
 *
 * @property int $id
 * @property string $name
 * @property int $role_id
 *
 * @property Role $role
 */
class Permission extends ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'permissions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'role_id'], 'required'],
            [['role_id'], 'integer'],
            [['name'], 'string', 'max' => 63],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::class, 'targetAttribute' => ['role_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'role_id' => 'Role ID',
        ];
    }

    /**
     * Gets query for [[Role]].
     *
     * @return ActiveQuery
     */
    public function getRole(): ActiveQuery
    {
        return $this->hasOne(Role::class, ['id' => 'role_id']);
    }

}
