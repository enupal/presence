<?php
/**
 * Presence plugin for Craft CMS 3.x
 *
 * @link      https://enupal.com
 * @copyright Copyright (c) 2020 Enupal
 */

namespace enupal\presence\services;
use craft\base\Component;
use Craft;
use craft\base\ElementInterface;
use craft\helpers\Db;
use enupal\presence\migrations\Install;

class Presence extends Component
{
    /**
     * @param ElementInterface $user
     * @param ElementInterface $element
     * @throws \yii\db\Exception
     */
    public function isAlive(ElementInterface $user, ElementInterface $element)
    {
        $connection = Craft::$app->getDb();

        $rowToInsert = [
            'userId' => $user->id,
            'elementId' => $element->id,
            'lastDateAlive' => Db::prepareDateForDb(new \DateTime())
        ];

        $connection->createCommand()->upsert(Install::SESSION_TABLE, $rowToInsert)->execute();
    }
}
