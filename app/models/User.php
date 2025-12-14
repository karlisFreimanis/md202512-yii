<?php

declare(strict_types=1);

namespace app\models;

use app\modules\task\models\Task;
use app\modules\user\models\Role;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property int|null $manager_id
 * @property string $username
 * @property string $first_name
 * @property string $last_name
 * @property int $access_level
 * @property string|null $birthday
 * @property string $password
 * @property string|null $auth_key
 *
 * @property Role $role
 * @property Task[] $tasks
 */
class User extends ActiveRecord implements IdentityInterface
{
    public const string SCENARIO_CREATE = 'create';
    public const string SCENARIO_UPDATE = 'update';
    public const string ATTRIBUTE_PASSWORD = 'password';
    public const string ATTRIBUTE_AUTH_KEY = 'auth_key';

    public function scenarios(): array
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_CREATE] = [
            'username',
            'first_name',
            'last_name',
            'access_level',
            'role_id',
            self::ATTRIBUTE_PASSWORD,
        ];

        $scenarios[self::SCENARIO_UPDATE] = [
            'username',
            'first_name',
            'last_name',
            'access_level',
            'birthday',
            'manager_id',
            'role_id',
            // to preserve password
        ];

        return $scenarios;
    }

    public function rules(): array
    {
        return [
            [['manager_id', 'birthday', self::ATTRIBUTE_AUTH_KEY], 'default', 'value' => null],
            [['access_level'], 'default', 'value' => 0],
            [['manager_id', 'access_level', 'role_id'], 'integer'],
            [['username', 'first_name', 'last_name'], 'required'],
            [['birthday'], 'safe'],
            [['username', self::ATTRIBUTE_PASSWORD], 'string', 'max' => 255],
            [['first_name', 'last_name'], 'string', 'max' => 100],
            [[self::ATTRIBUTE_AUTH_KEY], 'string', 'max' => 32],
            [['username'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'role_id' => 'Role',
            'manager_id' => 'Manager ID',
            'username' => 'Username',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'access_level' => 'Access Level',
            'birthday' => 'Birthday',
            self::ATTRIBUTE_PASSWORD => 'Password',
            self::ATTRIBUTE_AUTH_KEY => 'Auth Key',
        ];
    }

    public static function tableName(): string
    {
        return 'users';
    }

    public static function findIdentity($id): ?IdentityInterface
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null): ?IdentityInterface
    {
        return static::findOne([self::ATTRIBUTE_AUTH_KEY => $token]);
    }

    public static function findByUsername(string $username): static|null
    {
        return static::findOne(['username' => $username]);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAuthKey(): string
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey): bool
    {
        return $this->auth_key === $authKey;
    }

    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public function hashPassword(): User
    {
        $this->password = Yii::$app->security->generatePasswordHash($this->password);

        return $this;
    }

    public function hashAuthKey(): User
    {
        $this->auth_key = Yii::$app->security->generateRandomString();

        return $this;
    }

    public function getRole(): ActiveQuery
    {
        return $this->hasOne(Role::class, ['id' => 'role_id']);
    }

    public function fields(): array
    {
        $fields = parent::fields();

        if (Yii::$app->controller?->action?->id === 'index') {
            unset(
                $fields[User::ATTRIBUTE_AUTH_KEY],
                $fields[User::ATTRIBUTE_PASSWORD]
            );
        }

        return $fields;
    }

}
