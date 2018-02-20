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

namespace Cards\Common\Modules\Card\Application;

use yii\base\Model;

final class CardData extends Model
{
    /** @var string */
    public $name;

    /** @var string */
    public $description;

    public function rules()
    {
        return
            array_merge(
                parent::rules(),
                [
                    [['name', 'description'], 'required'],
                    [['name'], 'string', 'min' => 1, 'max' => 256],
                    [['description'], 'string', 'min' => 1, 'max' => 4096],
                ]
            );
    }
}