<?php

declare(strict_types=1);

namespace Cards\Common\Modules\Card\Domain;

interface CardCommandRepository
{
    public function add(Card $card): void;

    public function save(Card $card): void;

    public function findById(string $cardId): ?Card;
}