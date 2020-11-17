<?php
/**
 * Presence plugin for Craft CMS
 *
 * @link      https://enupal.com
 * @copyright Copyright (c) 2020 Enupal
 */

namespace enupal\presence\controllers;

use craft\web\Controller as BaseController;
use Craft;
use enupal\presence\Presence;

class PresenceController extends BaseController
{
    /**
     * Register session and return current users
     *
     * @return \yii\web\Response
     * @throws \yii\db\Exception
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionIsAlive()
    {
        $this->requireAcceptsJson();
        $response = [
            'success' => true,
            'errors' => []
        ];

        $userId = $this->request->getRequiredBodyParam('userId');
        $elementId = $this->request->getRequiredBodyParam('elementId');

        $user = Craft::$app->getElements()->getElementById($userId);
        $element = Craft::$app->getElements()->getElementById($elementId);

        if (is_null($user) || is_null($element)) {
            return $this->asJson([
                'success' => false,
                'errors' => ['User or Element not found']
            ]);
        }

        Presence::$app->presence->isAlive($user, $element);

        // Redirect back to page
        return $this->asJson($response);
    }
}
