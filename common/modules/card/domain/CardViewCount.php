<?php

declare(strict_types=1);

namespace Cards\Common\Modules\Card\Domain;

use yii\db\ActiveRecord;

/**
 * @property string $card_id
 * @property int $value
 */
final class CardViewCount extends ActiveRecord
{
    public static function tableName()
    {
        return 'card_view_count';
    }

    public static function create(string $cardId): self
    {
        $count = new self();
        $count->card_id = $cardId;
        $count->value = 0;

        return $count;
    }
}