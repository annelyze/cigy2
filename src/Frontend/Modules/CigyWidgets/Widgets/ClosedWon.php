<?php

namespace Frontend\Modules\CigyWidgets\Widgets;

use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;
use Frontend\Core\Language\Locale;

/**
 * This is the detail widget.
 */
class ClosedWon extends FrontendBaseWidget
{
    public function execute(): void
    {
        parent::execute();
        $this->loadTemplate();

        $closedWonData = array(
            'team' => '1.4M',
            'team_target' => '2.4M',
            'company' => '4.7M',
            'company_target' => '7.0M',
        );

        $this->template->assign('widgetClosedWon', $closedWonData);
    }
}
