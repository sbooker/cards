<?php

use Cards\Common\Modules\Card\Domain\Card;
use Cards\Common\Modules\Card\Domain\CardEvents;
use yii\db\Connection;
use yii\di\Instance;
use yii\base\Event;

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'container' => [
        'singletons' => [
            'card.view_count.application' => [
                ['class' => \Cards\Common\Modules\Card\Application\CardViewCountService::class],
                [
                    Instance::of('card.view_count.repository')
                ]
            ],
            'card.view_count.repository' =>
                \Cards\Common\Modules\Card\Infrastructure\CardViewCountOverArRepository::class
            ,
        ],
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db' => [
            'class' => yii\db\Connection::class,
            'dsn' => 'mysql:host=localhost;dbname=yourDBName',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'elasticsearch' => [
            'class' => yii\elasticsearch\Connection::class,
        ],
        'eventManager' => [
            'class'    => bariew\eventManager\EventManager::class,
            'events' => [
                Connection::class => [
                    Connection::EVENT_COMMIT_TRANSACTION => [
                        function (Event $event) {
                            /** @var \common\components\Event\TransactionalEvenHandler $handler */
                            $handler = Yii::$container->get('event.transactional_handler');
                            $handler->onCommitTransaction($event);
                        }
                    ],
                    Connection::EVENT_ROLLBACK_TRANSACTION => [
                        function (Event $event) {
                            /** @var \common\components\Event\TransactionalEvenHandler $handler */
                            $handler = Yii::$container->get('event.transactional_handler');
                            $handler->onRollbackTransaction($event);
                        }
                    ]
                ],
                Card::class => [
                    CardEvents::CARD_CREATED => [
                        function (Event $event) {
                            /** @var \Cards\Common\Modules\Card\Application\CardViewCountService $service */
                            $service = Yii::$container->get('card.view_count.application');
                            $service->create($event->sender);
                        }
                    ],
                ],
            ],
        ],
    ],
    'modules' => [
        'card' => \Cards\Common\Modules\Card\Module::class,
    ],
];
