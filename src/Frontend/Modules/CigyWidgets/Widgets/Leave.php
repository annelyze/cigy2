<?php

namespace Frontend\Modules\CigyWidgets\Widgets;

use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;

/**
 * This is the detail widget.
 */
class Leave extends FrontendBaseWidget
{
    public function execute(): void
    {
        parent::execute();
        $this->loadTemplate();

    }

}
