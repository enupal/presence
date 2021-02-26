<?php
/**
 * Presence plugin for Craft CMS 3.x
 *
 * @link      https://enupal.com
 * @copyright Copyright (c) 2020 Enupal
 */

namespace enupal\presence\variables;

use Craft;
use enupal\presence\Presence;

class PresenceVariable
{
    public function displayContainer()
    {
        return Presence::getInstance()->settings->displayContainer;
    }
}
