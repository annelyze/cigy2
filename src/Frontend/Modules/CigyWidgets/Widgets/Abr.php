<?php

namespace Frontend\Modules\CigyWidgets\Widgets;

use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;
use Frontend\Modules\CigyWidgets\Engine\Model as FrontendCigyWidgetsModel;

/**
 * This is the detail widget.
 */
class Abr extends FrontendBaseWidget
{
    public function execute(): void
    {
        parent::execute();
        $this->loadTemplate();

        $teamAbr = FrontendCigyWidgetsModel::GetAbr($this->pageTeamFilter);
        $companyAbr = FrontendCigyWidgetsModel::GetAbr(0);
        $teamtarget = FrontendCigyWidgetsModel::getYtdTarget($this->pageTeamFilter, 'ABR', 3);
        $companytarget = FrontendCigyWidgetsModel::getYtdTarget(0, 'ABR', 3);

        $abrData = array(
          'team' => $teamAbr["actual_ytd"],
          'team_target' => $teamtarget,
          'company' => $companyAbr["actual_ytd"],
          'company_target' => $companytarget,
        );

        $this->template->assign('widgetAbr', $abrData);
    }
}
