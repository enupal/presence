<?php
/**
 * Presence plugin for Craft CMS
 *
 * @link      https://enupal.com
 * @copyright Copyright (c) 2020 Enupal
 */

namespace enupal\presence\web;

use craft\web\AssetBundle;

class FontAwesomeAsset extends AssetBundle
{
    public function init()
    {
        // define the path that your publishable resources live
        $this->sourcePath = '@enupal/presence/resources/';

        // define the dependencies
        // define the relative path to CSS/JS files that should be registered with the page
        // when this asset bundle is registered

        $this->css = [
            'css/font/css/font-awesome.min.css'
        ];

        parent::init();
    }
}