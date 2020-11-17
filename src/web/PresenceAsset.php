<?php
/**
 * Presence plugin for Craft CMS
 *
 * @link      https://enupal.com
 * @copyright Copyright (c) 2018 Enupal
 */

namespace enupal\presence\web;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class PresenceAsset extends AssetBundle
{
    public function init()
    {
        // define the path that your publishable resources live
        $this->sourcePath = '@enupal/presence/resources/';

        // define the dependencies
        $this->depends = [
            CpAsset::class,
        ];

        // define the dependencies
        // define the relative path to CSS/JS files that should be registered with the page
        // when this asset bundle is registered
        $this->js = [
            'js/presence.js'
        ];

        parent::init();
    }
}