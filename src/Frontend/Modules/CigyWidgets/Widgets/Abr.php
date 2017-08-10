<?php

namespace Frontend\Modules\CigyWidgets\Widgets;

use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;
use Frontend\Core\Language\Locale;

/**
 * This is the detail widget.
 */
class Abr extends FrontendBaseWidget
{
    public function execute(): void
    {
        parent::execute();
        $this->loadTemplate();

        $abrData = array(
          'team' => '1.5M',
          'team_target' => '2.0M',
          'company' => '4.2M',
          'company_target' => '6.0M',
        );

        $this->template->assign('widgetAbr', $abrData);
    }
}
