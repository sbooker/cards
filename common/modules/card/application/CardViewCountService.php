<?php

declare(strict_types=1);

namespace Cards\Common\Modules\Card\Application;

use Cards\Common\Modules\Card\Domain\Card;
use Cards\Common\Modules\Card\Domain\CardViewCount;
use Cards\Common\Modules\Card\Domain\CardViewCountRepository;

final class CardViewCountService
{
    /** @var CardViewCountRepository */
    private $repository;

    public function __construct(CardViewCountRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(Card $card): void
    {
        $this->repository->add(CardViewCount::create($card->id));
    }

    /**
     * @param string $cardId
     *
     * @return CardViewCount
     * @throws CardViewCountNotFound
     */
    public function getById(string $cardId): CardViewCount
    {
        $count = $this->repository->findByCardId($cardId);
        if (!$count) {
            throw new CardViewCountNotFound();
        }

        return $count;
    }

    /**
     * @param string $cardId
     *
     * @return void
     * @throws CardViewCountNotFound
     */
    public function increment(string $cardId): void
    {
        $count = $this->getById($cardId);
        
        $this->repository->incrementValue($count);
    }
}