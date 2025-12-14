<?php

namespace app\modules\task\repositories;
use app\modules\task\models\Task;
use app\repositories\BaseRepository;

class TaskRepository extends BaseRepository
{
    protected string $modelClass = Task::class;
}