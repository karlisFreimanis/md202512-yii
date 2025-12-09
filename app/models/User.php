<?php

declare(strict_types=1);

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model for database-backed authentication.
 * Works with 'user' table.
 */
class User extends ActiveRecord implements IdentityInterface
{
    public const string SCENARIO_CREATE = 'create';
    public const string SCENARIO_UPDATE = 'update';

    public function scenarios(): array
    {
        $scenarios = parent::scenarios();

        $scenarios[self::SCENARIO_CREATE] = [
            'username',
            'first_name',
            'last_name',
            'access_level',
            'password',
        ];

        $scenarios[self::SCENARIO_UPDATE] = [
            'username',
            'first_name',
            'last_name',
            'access_level',
            'birthday',
            'manager_id',
            // to preserve password
        ];

        return $scenarios;
    }

    public function rules(): array
    {
        return [
            [['username', 'first_name', 'last_name', 'access_level'], 'required'],
            ['username', 'unique'],
            ['access_level', 'integer'],
            ['birthday', 'date', 'format' => 'php:Y-m-d'],
            ['manager_id', 'integer'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'username' => 'Username',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'access_level' => 'Access Level',
            'manager_id' => 'Manager',
            'birthday' => 'Birthday',
            'password' => 'Password',
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
        return static::findOne(['auth_key' => $token]);
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

}
