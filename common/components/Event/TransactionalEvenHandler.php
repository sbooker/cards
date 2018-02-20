<?php

namespace common\components\Event;

use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\Connection;

final class TransactionalEvenHandler implements EventHandler
{
    /** @var EventHandler */
    private $handler;

    /** @var Event[][]  */
    private $storage = [];

    public function __construct(EventHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @inheritdoc
     */
    public function handle(Event $event): void
    {
        if ($this->isEventInTransaction($event)) {
            $this->storeEvent($event);
        } else {
            $this->handler->handle($event);
        }
    }

    /**
     * @param Event $event
     *
     * @return void
     * @throws InvalidConfigException
     */
    public function onCommitTransaction(Event $event)
    {
        $connection = $event->sender;
        if (!$this->isCommitEvent($event) || !$connection instanceof Connection) {
            throw new InvalidConfigException(__METHOD__ . ' must be handler on ' . Connection::EVENT_COMMIT_TRANSACTION);
        }

        foreach ($this->getEventList($connection) as $storedEvent) {
            $this->handler->handle($storedEvent);
        }

        $this->clearConnectionEventList($connection);
    }

    /**
     * @param Event $event
     *
     * @return void
     * @throws InvalidConfigException
     */
    public function onRollbackTransaction(Event $event)
    {
        $connection = $event->sender;
        if (!$this->isRollbackEvent($event) || !$connection instanceof Connection) {
            throw new InvalidConfigException(__METHOD__ . ' must be handler on ' . Connection::EVENT_ROLLBACK_TRANSACTION);
        }

        $this->clearConnectionEventList($connection);
    }

    /**
     * @return bool
     */
    private function isEventInTransaction(Event $event)
    {
        $sender = $event->sender;

        return
            $sender instanceof ActiveRecord
                &&
            (bool)$sender->getDb()->getTransaction();
    }

    private function isCommitEvent(Event $event)
    {
        return $event->name == Connection::EVENT_COMMIT_TRANSACTION;
    }

    private function isRollbackEvent(Event $event)
    {
        return $event->name == Connection::EVENT_ROLLBACK_TRANSACTION;
    }
    
    private function storeEvent(Event $event)
    {
        /** @var ActiveRecord $sender */
        $sender = $event->sender;

        $this->storage[$this->getKey($sender->getDb())][] = $event;
    }

    /**
     * @param Connection $connection
     *
     * @return Event[]
     */
    private function getEventList(Connection $connection)
    {
        if ($this->hasConnectionEventList($connection)) {
            return $this->storage[$this->getKey($connection)];
        }

        return [];
    }

    /**
     * @param Connection $connection
     *
     * @return bool
     */
    private function hasConnectionEventList(Connection $connection)
    {
        return isset($this->storage[$this->getKey($connection)]);
    }

    /**
     * @param Connection $connection
     *
     * @return void
     */
    private function clearConnectionEventList(Connection $connection)
    {
        $this->storage[$this->getKey($connection)] = [];
    }

    /**
     * @param Connection $connection
     *
     * @return string
     */
    private function getKey(Connection $connection)
    {
        return spl_object_hash($connection);
    }
}