<?php


namespace app\modules\user\models;

use app\models\User;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "roles".
 *
 * @property int $id
 * @property string $name
 *
 * @property Permission $permissions
 * @property UserRole $userRoles
 * @property User $users
 */
class Role extends \yii\db\ActiveRecord
{

    public const string ROLE_ADMIN = 'admin';
    public const string ROLE_MANAGER = 'manager';
    public const string ROLE_EMPLOYEE = 'employee';
    public const ROLE_ALL = [
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

    /**
     * Gets query for [[Permissions]].
     *
     * @return ActiveQuery
     */
    public function getPermissions(): ActiveQuery
    {
        return $this->hasMany(Permission::class, ['role_id' => 'id']);
    }

    /**
     * Gets query for [[UserRoles]].
     *
     * @return ActiveQuery
     */
    public function getUserRoles(): ActiveQuery
    {
        return $this->hasMany(UserRole::class, ['role_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getUsers(): ActiveQuery
    {
        return $this->hasMany(User::class, ['id' => 'user_id'])->viaTable(
            'user_roles',
            ['role_id' => 'id']
        );
    }

}
