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
use craft\db\Query;
use craft\helpers\DateTimeHelper;
use craft\helpers\Db;
use enupal\presence\migrations\Install;

class Presence extends Component
{
    /**
     * Register session of user and element
     *
     * @param ElementInterface $user
     * @param ElementInterface $element
     * @return void
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

    /**
     * Return current users viewing the element
     *
     * @param ElementInterface $userToExclude
     * @param ElementInterface $element
     * @return array
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     * @throws \yii\base\Exception
     */
    public function getCurrentUsers(ElementInterface $userToExclude, ElementInterface $element)
    {
        $date = $this->getTodayDateSubtract10Seconds();
        $date = DateTimeHelper::toDateTime($date);
        $date->setTimezone(new \DateTimeZone('UTC'));
        $date10SecondsAgo = $date->format('Y-m-d H:i:s');

        $users = (new Query())
            ->select('userId')
            ->from(Install::SESSION_TABLE)
            ->where(['elementId' => $element->id])
            ->andWhere(['>=', 'lastDateAlive', $date10SecondsAgo])
            ->andWhere(['<>', 'userId', $userToExclude->id])
            ->limit(null)
            ->all();

        $view = Craft::$app->getView();
        $userPhotos = [];

        foreach ($users as $user) {
            $userElement = Craft::$app->getUsers()->getUserById($user['userId']);
            $userPhotos[$userElement->id] = $view->renderTemplate('enupal-presence/_presence/userphoto', ['user' => $userElement]);
        }

        return $userPhotos;
    }

    private function getTodayDateSubtract10Seconds()
    {
        $time = new \DateTime();
        $time->modify("-10 second");

        return $time;
    }
}
