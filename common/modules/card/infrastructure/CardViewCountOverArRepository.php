<?php
/**
 * Alfa Capital Holdings (Cyprus) Limited.
 *
 * The following source code is PROPRIETARY AND CONFIDENTIAL. Use of this source code
 * is governed by the Alfa Capital Holdings (Cyprus) Ltd. Non-Disclosure Agreement
 * previously entered between you and Alfa Capital Holdings (Cyprus) Limited.
 *
 * By accessing, using, copying, modifying or distributing this software, you acknowledge
 * that you have been informed of your obligations under the Agreement and agree
 * to abide by those obligations.
 *
 * @author "Sergey Knigin" <sergey.knigin@alfaforex.com>
 */

declare(strict_types=1);

namespace Cards\Common\Modules\Card\Infrastructure;

use Cards\Common\Modules\Card\Domain\CardViewCount;
use Cards\Common\Modules\Card\Domain\CardViewCountRepository;

final class CardViewCountOverArRepository implements CardViewCountRepository
{
    public function add(CardViewCount $count): void
    {
        $count->save(false);
    }

    public function findByCardId(string $cardId): ?CardViewCount
    {
        return CardViewCount::findOne(['card_id' => $cardId]);
    }

    public function incrementValue(CardViewCount $count): void
    {
        CardViewCount::getDb()->createCommand(
                sprintf(
                    "UPDATE %s SET value = value + 1 WHERE card_id = '%s'",
                    CardViewCount::tableName(),
                    $count->card_id
                )

        );
    }
}