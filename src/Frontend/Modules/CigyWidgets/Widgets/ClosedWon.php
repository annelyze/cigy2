<?php

namespace Frontend\Modules\CigyWidgets\Widgets;

use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;
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

        //$teamClosedWon = FrontendCigyServicesYadera::getClosedWon($this->pageTeamFilter);

        $closedWonData = array(
            'team' => 1800000,
            'team_target' => 2400000,
            'company' => 4700000,
            'company_target' => 7000000,
        );

        $this->template->assign('widgetClosedWon', $closedWonData);
    }
}
