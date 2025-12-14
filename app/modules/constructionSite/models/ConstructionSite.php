<?php

namespace app\modules\constructionSite\models;

use app\modules\task\models\Task;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "construction_sites".
 *
 * @property int $id
 * @property string $address
 * @property int $access_level
 * @property int $area
 *
 * @property Task[] $tasks
 */
class ConstructionSite extends ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'construction_sites';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['address', 'access_level', 'area'], 'required'],
            [['access_level', 'area'], 'integer'],
            [['address'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'address' => 'Address',
            'access_level' => 'Access Level',
            'area' => 'Area',
        ];
    }
}
