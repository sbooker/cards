<?php

declare(strict_types=1);

namespace Cards\Common\Modules\Card\Domain;

interface CardQueryRepository
{
    /**
     * @param int $quantity
     *
     * @return Card[]
     */
    public function findLastAdded(int $quantity): array;
}