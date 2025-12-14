<?php


namespace app\modules\user\models;

use app\models\User;

/**
 * This is the model class for table "roles".
 *
 * @property int $id
 * @property string $name
 *
 * @property User $users
 */
class Role extends \yii\db\ActiveRecord
{

    public const string ROLE_ADMIN = 'admin';
    public const string ROLE_MANAGER = 'manager';
    public const string ROLE_EMPLOYEE = 'employee';
    public const array ROLE_ALL = [
        self::ROLE_ADMIN,
        self::ROLE_MANAGER,
        self::ROLE_EMPLOYEE,
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'roles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 63],
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
        ];
    }
}
