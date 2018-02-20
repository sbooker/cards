<?php

declare(strict_types=1);

namespace Cards\Common\Modules\Card\Domain;

use Ramsey\Uuid\Uuid;
use yii\db\ActiveRecord;

/**
 * @property string id
 * @property string name
 * @property string description
 * @property string created_at
 * @property bool is_deleted
 */
final class Card extends ActiveRecord
{
    public static function tableName()
    {
        return 'card';
    }

    public static function create(string $name, string $description): self
    {
        $card = new self();
        $card->id = Uuid::uuid4()->toString();
        $card->name = $name;
        $card->description = $description;
        $card->created_at = new \DateTimeImmutable();
        $card->is_deleted = false;

        return $card;
    }

    public function markDeleted(): void
    {
        $this->is_deleted = true;
    }
}