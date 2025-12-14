<?php

use yii\db\Migration;

/**
 * Handles the creation of roles, users, and user_roles tables.
 */
/** @noinspection PhpUnused */
class m251209_095038_create_users_roles_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable('user_roles', [
            'user_id' => $this->integer()->notNull(),
            'role_id' => $this->integer()->notNull(),
            'PRIMARY KEY(user_id, role_id)',
        ]);

        // Add foreign keys
        $this->addForeignKey('fk_user_roles_user', 'user_roles', 'user_id', 'users', 'id', 'CASCADE');
        $this->addForeignKey('fk_user_roles_role', 'user_roles', 'role_id', 'roles', 'id', 'CASCADE');

        // Assign roles to first 3 users
        $this->batchInsert('user_roles', ['user_id', 'role_id'], [
            [1, 2], // admin -> admin
            [2, 3], // manager -> manager
            [3, 1],
            [4, 1],
            [5, 1],
            [6, 1],
            [7, 1],
            [8, 1],
            [9, 1],
            [10, 1],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropForeignKey('fk_user_roles_user', 'user_roles');
        $this->dropForeignKey('fk_user_roles_role', 'user_roles');
        $this->dropTable('user_roles');
    }
}
