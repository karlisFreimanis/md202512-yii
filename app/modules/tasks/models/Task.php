<?php

namespace app\modules\tasks\models;

use app\models\User;
use app\modules\constructionSite\models\ConstructionSite;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property bool|null $is_completed
 * @property string $name
 * @property int $user_id
 * @property int $construction_site_id
 * @property string|null $start_time
 * @property string|null $end_time
 *
 * @property ConstructionSite $constructionSite
 * @property User $user
 */
class Task extends ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['start_time', 'end_time'], 'default', 'value' => null],
            [['is_completed'], 'default', 'value' => 0],
            [['is_completed'], 'boolean'],
            [['name', 'user_id', 'construction_site_id'], 'required'],
            [['user_id', 'construction_site_id'], 'integer'],
            [['start_time', 'end_time'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['construction_site_id'], 'exist', 'skipOnError' => true, 'targetClass' => ConstructionSite::class, 'targetAttribute' => ['construction_site_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'is_completed' => 'Is Completed',
            'name' => 'Name',
            'user_id' => 'User ID',
            'construction_site_id' => 'Construction Site ID',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
        ];
    }

    /**
     * Gets query for [[ConstructionSite]].
     *
     * @return ActiveQuery
     */
    public function getConstructionSite(): ActiveQuery
    {
        return $this->hasOne(ConstructionSite::class, ['id' => 'construction_site_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}
