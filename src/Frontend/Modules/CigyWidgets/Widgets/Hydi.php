<?php

namespace Frontend\Modules\CigyWidgets\Widgets;

use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;
use Frontend\Modules\CigyWidgets\Services\Hydi as FrontendCigyServicesHydi;

/**
 * This is the detail widget.
 */
class Hydi extends FrontendBaseWidget
{
    public function execute(): void
    {
        parent::execute();
        $this->loadTemplate();

        // get api key
        $apiKey = FrontendCigyServicesHydi::getApiKey();

        $teamHydi = FrontendCigyServicesHydi::getHydi($apiKey, $this->pageTeamFilter);
        $companyHydi = FrontendCigyServicesHydi::getHydi($apiKey);

        $hydiData = array(
            'team' => $teamHydi,
            'company' => $companyHydi
        );

        $this->template->assign('widgetHydi', $hydiData);
    }

}
