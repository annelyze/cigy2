<?php

namespace ForkCMS\Bundle\InstallerBundle\Form\Handler;

use ForkCMS\Bundle\InstallerBundle\Entity\InstallationData;

/**
 * Validates and saves the data from the modules form
 */
final class ModulesHandler extends InstallerHandler
{
    public function processInstallationData(InstallationData $installationData): InstallationData
    {
        return $installationData;
    }
}
