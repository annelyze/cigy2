<?php

namespace Frontend\Modules\CigyWidgets\Engine;

use Frontend\Core\Engine\Model as FrontendModel;
use Frontend\Core\Engine\Navigation as FrontendNavigation;

/**
 * In this file we store all generic functions that we will be using in the faq module
 */
class Model
{
    /**
     * @later make this dynamic
     *
     * @param int $teamId
     * @return string
     */
    public static function getTeamName(int $teamId): string
    {
        $teams = array(
            0 => 'Wijs',
            1 => 'Teamtation',
            2 => 'Flaming Flamingo\'s',
            3 => 'Studio Eleanor',
            4 => 'Fireflies'
        );

        return (string) (array_key_exists($teamId, $teams) ? $teams[$teamId] : $teams[0]);
    }
}
