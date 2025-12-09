<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%construction_sites}}`.
 */
/** @noinspection PhpUnused */
class m251209_100223_create_construction_sites_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp(): void
    {
        $this->createTable('construction_sites', [
            'id' => $this->primaryKey(),
            'address' => $this->string()->notNull(),
            'access_level' => $this->integer()->notNull(),
            'area' => $this->integer()->notNull(),
        ]);

        $sites = [
            ['123 Main St', 1, 500],
            ['456 Oak Ave', 2, 1200],
            ['789 Pine Rd', 1, 800],
            ['321 Maple Ln', 3, 1500],
            ['654 Elm St', 2, 600],
            ['987 Cedar Blvd', 1, 900],
            ['159 Birch Way', 2, 700],
            ['753 Spruce Dr', 3, 1100],
            ['852 Walnut St', 1, 950],
            ['951 Cherry Ct', 2, 1000],
        ];

        $insertData = [];
        foreach ($sites as $s) {
            $insertData[] = [
                $s[0], // address
                $s[1], // access_level
                $s[2], // area
            ];
        }

        $this->batchInsert(
            'construction_sites',
            ['address', 'access_level', 'area'],
            $insertData
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown(): void
    {
        $this->dropTable('construction_sites');
    }
}
