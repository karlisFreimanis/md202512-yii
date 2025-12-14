<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users}}`.
 *
 */
/** @noinspection PhpUnused */
class m251209_085647_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     * @throws \yii\base\Exception
     */
    public function safeUp(): void
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'role_id' => $this->integer()->notNull(),
            'manager_id' => $this->integer()->null(),
            'username' => $this->string()->notNull()->unique(),
            'first_name' => $this->string(100)->notNull(),
            'last_name' => $this->string(100)->notNull(),
            'access_level' => $this->integer()->notNull()->defaultValue(0),
            'birthday' => $this->date()->null(),
            'password' => $this->string()->notNull(),
            'auth_key' => $this->string(32),
        ]);

        // Seed users
        $users = [
            ['admin', 2, 'John', 'Doe', '1990-01-01', 'admin', null],
            ['manager', 3, 'Jane', 'Smith', '1985-05-12', 'manager', null], // admin is manager
            ['employee', 1, 'Mike', 'Johnson', '1992-07-23', 'employee', 2], // manager is manager
            ['user4', 1, 'Emily', 'Brown', '1995-03-15', 'user4', 2],
            ['user5', 1, 'Chris', 'Davis', '1988-11-30', 'user5', 2],
            ['user6', 1, 'Anna', 'Miller', '1991-09-05', 'user6', null],
            ['user7', 1, 'Tom', 'Wilson', '1993-02-20', 'user7', null],
            ['user8', 1, 'Laura', 'Moore', '1989-08-14', 'user8', null],
            ['user9', 1, 'James', 'Taylor', '1994-12-09', 'user9', 2],
            ['user10', 1, 'Olivia', 'Anderson', '1996-06-17', 'user10', 2],
        ];

        $insertData = [];
        foreach ($users as $u) {
            $insertData[] = [
                $u[0], // username
                $u[1], // role_id
                $u[2], // first_name
                $u[3], // last_name
                $u[4], // birthday
                Yii::$app->security->generatePasswordHash($u[5]), // hashed password
                Yii::$app->security->generateRandomString(), // auth_key
                $u[6], // manager_id
            ];
        }

        $this->batchInsert(
            'users',
            ['username', 'role_id', 'first_name', 'last_name', 'birthday', 'password', 'auth_key', 'manager_id'],
            $insertData
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropTable('users');
    }
}
