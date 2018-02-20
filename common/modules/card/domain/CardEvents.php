<?php

declare(strict_types=1);

namespace Cards\Common\Modules\Card\Domain;

final class CardEvents
{
    const
        CARD_CREATED = 'cardCreated',
        CARD_UPDATED = 'cardUpdated',
        CARD_DELETED = 'cardDeleted'
    ;
}