<?php

use yii\db\Migration;

/** @noinspection PhpUnused */
class m251209_094640_create_roles extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable('roles', [
            'id' => $this->primaryKey(),
            'name' => $this->string(63)->notNull(),
        ]);

        // Insert default roles
        $this->batchInsert('roles', ['name'], [
            ['employee'],
            ['admin'],
            ['manager'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropTable('roles');
    }

}
