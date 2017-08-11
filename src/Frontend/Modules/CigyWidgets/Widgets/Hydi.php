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

        $teamHydi = '-';
        $companyHydi = '-';

        try {
            // get api key
            $apiKey = FrontendCigyServicesHydi::getApiKey();

            // get numbers
            $teamHydi = FrontendCigyServicesHydi::getHydi($apiKey, $this->pageTeamFilter);
            $companyHydi = FrontendCigyServicesHydi::getHydi($apiKey);
        } catch (Exception $e) {}

        $hydiData = array(
            'team' => $teamHydi,
            'company' => $companyHydi
        );

        $this->template->assign('widgetHydi', $hydiData);
    }

}
