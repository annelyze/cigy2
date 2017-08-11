<?php

namespace Frontend\Modules\CigyWidgets\Widgets;

use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;
use Frontend\Modules\CigyWidgets\Engine\Model as FrontendCigyWidgetsModel;

/**
 * This is the detail widget.
 */
class Image extends FrontendBaseWidget
{
    public function execute(): void
    {
        parent::execute();
        $this->loadTemplate();

        $npsData = array(
            'image' => 'Frontend/Themes/Code/Layout/Images/WidgetImages/partyparrot.gif',
        );

        $this->template->assign('widgetImage', $npsData);
    }

}
