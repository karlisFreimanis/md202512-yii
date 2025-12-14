<?php

declare(strict_types=1);

namespace app\repositories;

use yii\db\ActiveRecord;
use yii\db\Exception;

abstract class BaseRepository
{
    protected string $modelClass;

    /**
     * @param array $data
     * @return ActiveRecord
     * @throws Exception
     */
    public function create(array $data): ActiveRecord
    {
        /** @var ActiveRecord $model */
        $model = new $this->modelClass();
        $model->load($data);

        if (!$model->save()) {
            throw new Exception('Create failed: ' . json_encode($model->errors));
        }

        return $model;
    }

    /**
     * @param int $id
     * @return bool
     * @throws Exception|\Throwable
     */
    public function delete(int $id): bool
    {
        /** @var ActiveRecord $model */
        $model = $this->modelClass::findOne($id);
        if (!$model) {
            throw new Exception('Record not found');
        }

        return (bool)$model->delete();
    }

    /**
     * @param int $id
     * @param array $data
     * @return ActiveRecord
     * @throws Exception
     */
    public function update(int $id, array $data): ActiveRecord
    {
        /** @var ActiveRecord $model */
        $model = $this->modelClass::findOne($id);
        if (!$model) {
            throw new Exception('Record not found');
        }

        $model->load($data);
        if (!$model->save()) {
            throw new Exception('Update failed: ' . json_encode($model->errors));
        }

        return $model;
    }

    public function getName(): string
    {
        return (new \ReflectionClass($this->modelClass))->getShortName();
    }
}