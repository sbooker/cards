<?php

declare(strict_types=1);

namespace common\components\Event;

use yii\base\Event;

interface EventHandler
{
    public function handle(Event $event): void;
}