<?php

namespace Backend\Modules\CigyWidgets\Installer;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Backend\Core\Installer\ModuleInstaller;
use Common\ModuleExtraType;

/**
 * Installer for the content blocks module
 */
class Installer extends ModuleInstaller
{
    public function install(): void
    {
        $this->addModule('CigyWidgets');
        $this->importSQL(__DIR__ . '/Data/install.sql');
        $this->importLocale(__DIR__ . '/Data/locale.xml');
        $this->configureBackendRights();
        $this->configureFrontendExtras();
    }

    private function configureBackendRights(): void
    {
        $this->setModuleRights(1, $this->getModule());
    }

    private function configureFrontendExtras(): void
    {
        $this->insertExtra($this->getModule(), ModuleExtraType::widget(), 'NPS', 'Nps');
        $this->insertExtra($this->getModule(), ModuleExtraType::widget(), 'ABR', 'Abr');
        $this->insertExtra($this->getModule(), ModuleExtraType::widget(), 'ClosedWon', 'ClosedWon');
    }
}
