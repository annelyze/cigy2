<?php

namespace Frontend\Modules\CigyWidgets\Widgets;

use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;
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

        $npsData = array(
            'team' => $teamNps,
            'company' => $companyNps,
            'target' => '9',
        );

        $this->template->assign('widgetNps', $npsData);
    }

}
