<?php
/**
 * Presence plugin for Craft CMS
 *
 * @link      https://enupal.com
 * @copyright Copyright (c) 2020 Enupal
 */

namespace enupal\presence\models;

use craft\base\Model;

class Settings extends Model
{
    public $refreshSessionToken = 6000;
    public $displayContainer = '#details';
}
