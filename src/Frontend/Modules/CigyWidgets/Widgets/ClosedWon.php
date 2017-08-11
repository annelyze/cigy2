<?php

namespace Frontend\Modules\CigyWidgets\Widgets;

use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;

use Frontend\Core\Language\Locale;
use Frontend\Modules\CigyWidgets\Engine\Model as FrontendCigyWidgetsModel;
use Frontend\Modules\CigyWidgets\Services\Yadera as FrontendCigyServicesYadera;

/**
 * This is the detail widget.
 */
class ClosedWon extends FrontendBaseWidget
{
    public function execute(): void
    {
        parent::execute();
        $this->loadTemplate();
        //TODO: 
        //$teamClosedWon = FrontendCigyServicesYadera::getClosedWon($this->pageTeamFilter);

        $teamClosedWon = FrontendCigyWidgetsModel::getClosedWon($this->pageTeamFilter);
        $companyClosedWon = FrontendCigyWidgetsModel::getClosedWon(0);
        $teamtarget = FrontendCigyWidgetsModel::getYtdTarget($this->pageTeamFilter, 'ClosedWon', 3);
        $companytarget = FrontendCigyWidgetsModel::getYtdTarget(0, 'ClosedWon', 3);

        $closedWonData = array(
            'team' => $teamClosedWon["actual_ytd"],
            'team_target' => $teamtarget,
            'company' => $companyClosedWon["actual_ytd"],
            'company_target' => $companytarget,
        
        );

        $this->template->assign('widgetClosedWon', $closedWonData);
    }
}
