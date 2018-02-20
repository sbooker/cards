<?php

declare(strict_types=1);

namespace Cards\Common\Modules\Card\Infrastructure;

use Cards\Common\Modules\Card\Domain\Card;
use Cards\Common\Modules\Card\Domain\CardCommandRepository;

final class CardOverArRepository implements CardCommandRepository
{
    public function add(Card $card): void
    {
        $card->save(false);
    }

    public function save(Card $card): void
    {
        $card->save(false);
    }

    public function findById(string $cardId): ?Card
    {
        return Card::findOne(['id' => $cardId]);
    }
}