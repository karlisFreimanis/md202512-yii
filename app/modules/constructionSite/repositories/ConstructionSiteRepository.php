<?php

namespace app\modules\constructionSite\repositories;

use app\modules\constructionSite\models\ConstructionSite;
use app\repositories\BaseRepository;

class ConstructionSiteRepository extends BaseRepository
{
    protected string $modelClass = ConstructionSite::class;
}