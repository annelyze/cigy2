<?php

namespace Frontend\Modules\CigyWidgets\Widgets;

use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;
use Frontend\Modules\CigyWidgets\Engine\Model as FrontendCigyWidgetsModel;
use Frontend\Modules\CigyWidgets\Services\CustomerGauge as FrontendCigyServicesCustomerGauge;

/**
 * This is the detail widget.
 */
class Nps extends FrontendBaseWidget
{
    public function execute(): void
    {
        parent::execute();
        $this->loadTemplate();

        $teamNps = FrontendCigyServicesCustomerGauge::getNps($this->pageTeamFilter);
        $companyNps = FrontendCigyServicesCustomerGauge::getNps();
        //The NPS target is a company target so we collect the company target for NPS instead of the team target
        $target = FrontendCigyWidgetsModel::getYtdTarget(0, 'NPS', 3);

        $npsData = array(
            'team' => $teamNps,
            'company' => $companyNps,
            'target' => $target,
        );

        $this->template->assign('widgetNps', $npsData);
    }

}
