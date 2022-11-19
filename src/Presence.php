<?php
/**
 * Presence plugin for Craft CMS
 *
 * @link      https://enupal.com
 * @copyright Copyright (c) 2020 Enupal
 */

namespace enupal\presence;

use Craft;
use craft\base\Element;
use craft\base\Plugin;
use craft\elements\Entry;
use craft\events\DefineHtmlEvent;
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

    public string $schemaVersion = '2.0.0';

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

        // Register presence
        Event::on(
            Element::class,
            Element::EVENT_DEFINE_SIDEBAR_HTML,
            function (DefineHtmlEvent $event) {
                $entry = $event->sender;
                if (get_class($entry) !== Entry::class) {
                    return null;
                }

                $view = Craft::$app->getView();
                $view->renderTemplate('enupal-presence/_session/is-alive', ['entryId' => $entry->id]);
            }
        );
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

