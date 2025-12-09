<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%permissions}}`.
 */
/** @noinspection PhpUnused */
class m251209_095732_create_permissions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable('permissions', [
            'id' => $this->primaryKey(),
            'name' => $this->string(63)->notNull(),
            'role_id' => $this->integer()->notNull(),
        ]);

        //todo seed

        $this->addForeignKey('fk_permissions_roles', 'permissions', 'role_id', 'roles', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropForeignKey('fk_permissions_roles', 'permissions');
        $this->dropTable('permissions');
    }
}
