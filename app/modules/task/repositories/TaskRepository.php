<?php

namespace app\modules\task\repositories;
use app\models\User;
use app\modules\constructionSite\models\ConstructionSite;
use app\modules\task\models\Task;
use app\modules\user\models\Role;
use app\repositories\BaseRepository;

class TaskRepository extends BaseRepository
{
    protected string $modelClass = Task::class;

    /**
     * @param User $user
     * @return Task[]
     */
    public function getUserTasks(User $user): array
    {
        return match ($user->role->name) {
            Role::ROLE_ADMIN => Task::find()->all(),
            Role::ROLE_MANAGER => $this->getManagerTasks($user),
            Role::ROLE_EMPLOYEE => Task::find()
                ->where(['user_id' => $user->id])
                ->all(),
            default => [],
        };
    }

    /**
     * @param User $user
     * @return Task[]
     */
    private function getManagerTasks(User $user): array
    {
        $constructionSites = ConstructionSite::find()
            ->where(['<=', 'access_level', $user->access_level])
            ->all();
        return Task::find()
            ->where(['construction_site_id' => array_column($constructionSites, 'id')])
            ->all();
    }

}