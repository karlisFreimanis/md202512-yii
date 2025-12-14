<?php

namespace app\modules\constructionSite\repositories;

use app\models\User;
use app\modules\constructionSite\models\ConstructionSite;
use app\modules\task\models\Task;
use app\modules\user\models\Role;
use app\repositories\BaseRepository;

class ConstructionSiteRepository extends BaseRepository
{
    protected string $modelClass = ConstructionSite::class;

    /**
     * @param User $user
     * @return ConstructionSite[]
     */
    public function getUserConstructionSites(User $user): array
    {
        return match ($user->role->name) {
            Role::ROLE_ADMIN => ConstructionSite::find()->all(),
            Role::ROLE_MANAGER => ConstructionSite::find()
                ->where(['<=', 'access_level', $user->access_level])
                ->all(),
            Role::ROLE_EMPLOYEE => $this->getEmployeeConstructionSites($user),
            default => [],
        };
    }

    /**
     * @param User $user
     * @return ConstructionSite[]
     */
    public function getEmployeeConstructionSites(User $user): array
    {
        $tasks = Task::find()->where(['user_id' => $user->id])->all();
        return ConstructionSite::find()
            ->where(['id' => array_unique(array_column($tasks, 'id'))])
            ->all();
    }
}