<?php

declare(strict_types=1);

namespace Cards\Common\Modules\Card\Application;

use Cards\Common\Modules\Card\Domain\Card;
use Cards\Common\Modules\Card\Domain\CardCommandRepository;
use Cards\Common\Modules\Card\Domain\CardEvents;
use Cards\Common\Modules\Card\Domain\CardQueryRepository;
use yii\base\Event;
use yii\db\Connection;

final class CardService
{
    /** @var Connection */
    private $connection;

    /** @var CardCommandRepository */
    private $commandRepository;

    /** @var CardQueryRepository */
    private $queryRepository;

    public function __construct(Connection $connection, CardCommandRepository $commandRepository, CardQueryRepository $queryRepository)
    {
        $this->connection = $connection;
        $this->commandRepository = $commandRepository;
        $this->queryRepository = $queryRepository;
    }

    /**
     * @param string $cardId
     *
     * @return Card
     * @throws CardNotFound
     */
    public function getById(string $cardId): Card
    {
        $card = $this->commandRepository->findById($cardId);

        if (!$card) {
            throw new CardNotFound();
        }

        return $card;
    }

    /**
     * @param CardData $data
     *
     * @return Card
     * @throws \Throwable
     */
    public function create(CardData $data): Card
    {
        $transaction = $this->connection->beginTransaction();
        try {
            $card = Card::create($data->name, $data->description);
            $this->commandRepository->add($card);
            Event::trigger($card, CardEvents::CARD_CREATED);
            $transaction->commit();

            return $card;
        } catch (\Exception $e) {
            $transaction->rollback();
            throw $e;
        }  catch (\Throwable $e) {
           $transaction->rollBack();
           throw $e;
        }
    }

    /**
     * @param string $cardId
     * @param CardData $data
     *
     * @return Card
     * @throws \Throwable
     */
    public function update(string $cardId, CardData $data): Card
    {
        $transaction = $this->connection->beginTransaction();
        try {
            $card = $this->getById($cardId);
            $card->name = $data->name;
            $card->description = $data->description;
            $this->commandRepository->save($card);
            Event::trigger($card, CardEvents::CARD_UPDATED);
            $transaction->commit();

            return $card;
        } catch (\Exception $e) {
            $transaction->rollback();
            throw $e;
        }  catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * @param string $cardId
     *
     * @return void
     * @throws \Throwable
     */
    public function delete(string $cardId): void
    {
        $transaction = $this->connection->beginTransaction();
        try {
            $card = $this->getById($cardId);
            $card->markDeleted();
            $this->commandRepository->save($card);
            Event::trigger($card, CardEvents::CARD_DELETED);
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            throw $e;
        }  catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * @param int $quantity
     *
     * @return Card[]
     */
    public function findLastAdded(int $quantity): array
    {
        return $this->queryRepository->findLastAdded($quantity);
    }
}