<?php

namespace Frontend\Modules\CigyWidgets\Widgets;

use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;
use Frontend\Modules\CigyWidgets\Engine\Model as FrontendCigyWidgetsModel;

/**
 * This is the detail widget.
 */
class Image extends FrontendBaseWidget
{
    private $imageLocation = "/src/Frontend/Themes/Fork/Core/Layout/Images/WidgetImages/";
    public function execute(): void
    {
        parent::execute();
        $this->loadTemplate();

        $randomTeamImage = FrontendCigyWidgetsModel::getRandomTeamImage($this->pageTeamFilter);

        $title = $randomTeamImage['title'];
        $subtitle = $randomTeamImage['subtitle'];
        $imagefile = $randomTeamImage['imagefile'];
        
        $imageData = array(
            'title' => $title,
            'subtitle' => $subtitle,
            'image' => $this->imageLocation . $imagefile,
        );
        
        $this->template->assign('widgetImage', $imageData);
    }

}
