<?php
/**
 * Presence plugin for Craft CMS
 *
 * @link      https://enupal.com
 * @copyright Copyright (c) 2020 Enupal
 */

namespace enupal\presence\services;

use craft\base\Component;

class App extends Component
{
    /**
     * @var Presence
     */
    public $presence;

    /**
     * @var Settings
     */
    public $settings;

    public function init()
    {
        $this->presence = new Presence();
        $this->settings = new Settings();
    }
}