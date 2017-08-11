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

    public static function getYtdTarget(int $team, string $targettype, int $level): int
    {
        $target = (int) FrontendModel::getContainer()->get('database')->getVar(
            "SELECT value_ytd from cigy_targets
            WHERE target = ?
            AND team = ?
            AND month = ?
            AND `level` = ?",
            [$targettype, $team, intval(date('m')), $level]
        );

        return $target;
    }

    public static function getTarget(int $team, string $targettype, int $level): int
    {
        return (int) FrontendModel::getContainer()->get('database')->getVar(
            "SELECT value from cigy_targets
            WHERE target = ?
            AND team = ?
            AND month = ?
            AND `level` = ?",
            [$targettype, $team, intval(date('m')), $level]
        );
    }

    public static function GetAbr(int $team): Array
    {
        return (Array) FrontendModel::getContainer()->get('database')->getRecord(
            "SELECT period, actual_ytd from cigy_abr_actuals
            WHERE team = ?",
            [$team]
        );
    }

    public static function getClosedWon(int $team): Array
    {
        return (Array) FrontendModel::getContainer()->get('database')->getRecord(
            "SELECT actual, actual_ytd from cigy_closedwon_actuals
            WHERE team = ?
            AND month = ?",
            [$team, intval(date('m'))]
        );
    }
}
