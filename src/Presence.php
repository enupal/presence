<?php
/**
 * Presence plugin for Craft CMS
 *
 * @link      https://enupal.com
 * @copyright Copyright (c) 2020 Enupal
 */

namespace enupal\presence;

use Craft;
use craft\base\Plugin;
use craft\web\twig\variables\CraftVariable;
use enupal\presence\services\App;
use enupal\presence\variables\PresenceVariable;
use yii\base\Event;

use enupal\presence\models\Settings;

class Presence extends Plugin
{
    /**
     * Enable use of Presence::$app-> in place of Craft::$app->
     *
     * @var App
     */
    public static $app;

    public bool $hasCpSection = false;

    public bool $hasCpSettings = false;

    public string $schemaVersion = '1.0.0';

    public function init()
    {
        parent::init();

        self::$app = $this->get('app');

        // Register our variables
        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('presence', PresenceVariable::class);
            }
        );

        Craft::$app->view->hook('cp.entries.edit.meta', function(array &$context) {
            $view = Craft::$app->getView();
            return $view->renderTemplate('enupal-presence/_session/is-alive', ['entryId' => $context['entryId']]);
        });
    }

    protected function createSettingsModel(): ?\craft\base\Model
    {
        return new Settings();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'enupal-presence/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}

