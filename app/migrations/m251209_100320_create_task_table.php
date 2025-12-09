<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task}}`.
 */
/** @noinspection PhpUnused */
class m251209_100320_create_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable('tasks', [
            'id' => $this->primaryKey(),
            //status would be better, but I don't want any more tables
            'is_completed' => $this->boolean()->defaultValue(0),
            'name' => $this->string()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'construction_site_id' => $this->integer()->notNull(),
            'start_time' => $this->timestamp()->null(),
            'end_time' => $this->timestamp()->null(),
        ]);

        $this->addForeignKey(
            'fk_tasks_construction_sites',
            'tasks',
            'construction_site_id',
            'construction_sites',
            'id',
        );
        $this->addForeignKey('fk_tasks_users', 'tasks', 'user_id', 'users', 'id');

        $tasks = [];
        for ($i = 1; $i <= 10; $i++) {
            $tasks[] = [
                "Task $i",        // name
                $i,               // user_id
                $i,               // construction_site_id
                date('Y-m-d H:i:s', strtotime("+$i days")), // start_time
                date('Y-m-d H:i:s', strtotime("+".($i+1)." days")), // end_time
            ];
        }

        $this->batchInsert(
            'tasks',
            ['name', 'user_id', 'construction_site_id', 'start_time', 'end_time'],
            $tasks
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropForeignKey('fk_tasks_construction_sites', 'tasks');
        $this->dropForeignKey('fk_tasks_construction_sites', 'tasks');
        $this->dropTable('tasks');
    }
}
