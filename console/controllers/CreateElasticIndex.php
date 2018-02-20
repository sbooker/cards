<?php

declare(strict_types=1);

namespace Cards\console\controllers;

use Cards\Common\Modules\Card\Domain\CardSearch;
use yii\console\Controller;

class CreateElasticIndex extends Controller
{
    public function actionIndex()
    {
        CardSearch::createIndex();
    }
}