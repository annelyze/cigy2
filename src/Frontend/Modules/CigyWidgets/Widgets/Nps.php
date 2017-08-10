<?php

namespace Frontend\Modules\CigyWidgets\Widgets;

use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;
use Frontend\Modules\CigyWidgets\Engine\Model as FrontendCigyWidgetsModel;

/**
 * This is the detail widget.
 */
class Nps extends FrontendBaseWidget
{
    public function execute(): void
    {
        parent::execute();
        $this->loadTemplate();

        $npsData = array(
            'team_name' => FrontendCigyWidgetsModel::getTeamName($this->pageTeamFilter),
            'team' => '16',
            'company' => '-1',
            'target' => '9',
        );

        $this->template->assign('widgetNps', $npsData);
    }
}
