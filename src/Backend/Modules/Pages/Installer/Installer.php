<?php

namespace Backend\Modules\Pages\Installer;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Backend\Core\Installer\ModuleInstaller;
use Common\ModuleExtraType;

/**
 * Installer for the pages module
 */
class Installer extends ModuleInstaller
{
    /** @var array */
    private $extraIds;

    public function install(): void
    {
        $this->addModule('Pages');
        $this->importSQL(__DIR__ . '/Data/install.sql');
        $this->importLocale(__DIR__ . '/Data/locale.xml');
        $this->configureBackendNavigation();
        $this->configureBackendRights();
        $this->configureFrontendPages();
    }

    private function configureBackendNavigation(): void
    {
        // Set navigation for "Pages"
        $this->setNavigation(null, $this->getModule(), 'pages/index', ['pages/add', 'pages/edit'], 2);

        // Set navigation for "Settings"
        $navigationSettingsId = $this->setNavigation(null, 'Settings');
        $navigationModulesId = $this->setNavigation($navigationSettingsId, 'Modules');
        $this->setNavigation($navigationModulesId, $this->getModule(), 'pages/settings');
    }

    private function configureBackendRights(): void
    {
        $this->setModuleRights(1, $this->getModule());

        $this->setActionRights(1, $this->getModule(), 'Add');
        $this->setActionRights(1, $this->getModule(), 'Delete');
        $this->setActionRights(1, $this->getModule(), 'Edit');
        $this->setActionRights(1, $this->getModule(), 'GetInfo'); // AJAX
        $this->setActionRights(1, $this->getModule(), 'Index');
        $this->setActionRights(1, $this->getModule(), 'Move'); // AJAX
        $this->setActionRights(1, $this->getModule(), 'RemoveUploadedFile'); // AJAX
        $this->setActionRights(1, $this->getModule(), 'Settings');
        $this->setActionRights(1, $this->getModule(), 'UploadFile'); // AJAX
    }

    private function configureFrontendPages(): void
    {
        // loop languages
        foreach ($this->getLanguages() as $language) {
            // check if pages already exist for this language
            if ($this->hasPage($language)) {
                continue;
            }

            // insert homepage
            $this->insertPage(
                [
                    'id' => 1,
                    'parent_id' => 0,
                    'template_id' => $this->getTemplateId('home'),
                    'title' => \SpoonFilter::ucfirst($this->getLocale('Home', 'Core', $language, 'lbl', 'Backend')),
                    'language' => $language,
                    'allow_move' => false,
                    'allow_delete' => false,
                ],
                null,
                ['html' => __DIR__ . '/Data/' . $language . '/sample1.txt']
            );

            // insert 404
            $this->insertPage(
                [
                    'id' => 404,
                    'title' => '404',
                    'template_id' => $this->getTemplateId('error'),
                    'type' => 'root',
                    'language' => $language,
                    'allow_move' => false,
                    'allow_delete' => false,
                ],
                null,
                ['html' => __DIR__ . '/Data/' . $language . '/404.txt']
            );
        }
    }

    private function getExtraId(string $key): int
    {
        if (!array_key_exists($key, $this->extraIds)) {
            throw new \Exception('Key not set yet, please check your installer.');
        }

        return $this->extraIds[$key];
    }

    private function hasPage(string $language): bool
    {
        // @todo: Replace with PageRepository method when it exists.
        return (bool) $this->getDatabase()->getVar(
            'SELECT 1 FROM pages WHERE language = ? AND id > ? LIMIT 1',
            [$language, 404]
        );
    }
}
