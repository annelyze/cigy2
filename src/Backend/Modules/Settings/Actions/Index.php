<?php

namespace Backend\Modules\Settings\Actions;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Backend\Core\Engine\Base\ActionIndex as BackendBaseActionIndex;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Language\Language as BL;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Extensions\Engine\Model as BackendExtensionsModel;
use Backend\Modules\Settings\Engine\Model as BackendSettingsModel;

/**
 * This is the index-action (default), it will display the setting-overview
 */
class Index extends BackendBaseActionIndex
{
    /**
     * The form instance
     *
     * @var BackendForm
     */
    private $form;


    public function execute(): void
    {
        parent::execute();

        $this->loadForm();
        $this->validateForm();
        $this->parse();
        $this->display();
    }

    private function loadForm(): void
    {
        // list of default domains
        $defaultDomains = [str_replace(['http://', 'www.', 'https://'], '', SITE_URL)];

        // create form
        $this->form = new BackendForm('settingsIndex');

        // general settings
        $this->form->addText(
            'site_title',
            $this->get('fork.settings')->get('Core', 'site_title_' . BL::getWorkingLanguage(), SITE_DEFAULT_TITLE)
        );
        $this->form->addTextarea(
            'site_html_header',
            $this->get('fork.settings')->get('Core', 'site_html_header', null),
            'form-control code',
            'form-control danger code',
            true
        );
        $this->form->addTextarea(
            'site_start_of_body_scripts',
            $this->get('fork.settings')->get('Core', 'site_start_of_body_scripts', null),
            'form-control code',
            'form-control danger code',
            true
        );
        $this->form->addTextarea(
            'site_html_footer',
            $this->get('fork.settings')->get('Core', 'site_html_footer', null),
            'form-control code',
            'form-control danger code',
            true
        );
        $this->form->addTextarea(
            'site_domains',
            implode("\n", (array) $this->get('fork.settings')->get('Core', 'site_domains', $defaultDomains)),
            'form-control code',
            'form-control danger code'
        );

        // date & time formats
        $this->form->addDropdown(
            'time_format',
            BackendModel::getTimeFormats(),
            $this->get('fork.settings')->get('Core', 'time_format')
        );
        $this->form->addDropdown(
            'date_format_short',
            BackendModel::getDateFormatsShort(),
            $this->get('fork.settings')->get('Core', 'date_format_short')
        );
        $this->form->addDropdown(
            'date_format_long',
            BackendModel::getDateFormatsLong(),
            $this->get('fork.settings')->get('Core', 'date_format_long')
        );

        // number formats
        $this->form->addDropdown(
            'number_format',
            BackendModel::getNumberFormats(),
            $this->get('fork.settings')->get('Core', 'number_format')
        );

        // create a list of the languages
        foreach ($this->get('fork.settings')->get('Core', 'languages', ['en']) as $abbreviation) {
            // is this the default language
            $defaultLanguage = $abbreviation === SITE_DEFAULT_LANGUAGE;

            // attributes
            $activeAttributes = [];
            $activeAttributes['id'] = 'active_language_' . $abbreviation;
            $redirectAttributes = [];
            $redirectAttributes['id'] = 'redirect_language_' . $abbreviation;

            // fetch label
            $label = BL::lbl(mb_strtoupper($abbreviation), 'Core');

            // default may not be unselected
            if ($defaultLanguage) {
                // add to attributes
                $activeAttributes['disabled'] = 'disabled';
                $redirectAttributes['disabled'] = 'disabled';

                // overrule in $_POST
                if (!isset($_POST['active_languages']) || !is_array($_POST['active_languages'])) {
                    $_POST['active_languages'] = [SITE_DEFAULT_LANGUAGE];
                } elseif (!in_array(
                    $abbreviation,
                    $_POST['active_languages']
                )
                ) {
                    $_POST['active_languages'][] = $abbreviation;
                }
                if (!isset($_POST['redirect_languages']) || !is_array($_POST['redirect_languages'])) {
                    $_POST['redirect_languages'] = [SITE_DEFAULT_LANGUAGE];
                } elseif (!in_array(
                    $abbreviation,
                    $_POST['redirect_languages']
                )
                ) {
                    $_POST['redirect_languages'][] = $abbreviation;
                }
            }

            // add to the list
            $activeLanguages = [
                [
                    'label' => $label,
                    'value' => $abbreviation,
                    'attributes' => $activeAttributes,
                    'variables' => ['default' => $defaultLanguage],
                ],
                $redirectLanguages[] = [
                    'label' => $label,
                    'value' => $abbreviation,
                    'attributes' => $redirectAttributes,
                    'variables' => ['default' => $defaultLanguage],
                ],
            ];
        }

        $hasMultipleLanguages = BackendModel::getContainer()->getParameter('site.multilanguage');

        // create multilanguage checkbox
        $this->form->addMultiCheckbox(
            'active_languages',
            $activeLanguages,
            $this->get('fork.settings')->get('Core', 'active_languages', [$hasMultipleLanguages])
        );
        $this->form->addMultiCheckbox(
            'redirect_languages',
            $redirectLanguages,
            $this->get('fork.settings')->get('Core', 'redirect_languages', [$hasMultipleLanguages])
        );
    }

    protected function parse(): void
    {
        parent::parse();

        // parse the form
        $this->form->parse($this->template);

        // parse the warnings
        $this->parseWarnings();
    }

    /**
     * Show the warnings based on the active modules & configured settings
     */
    private function parseWarnings(): void
    {
        // get warnings
        $warnings = BackendSettingsModel::getWarnings();

        // assign warnings
        $this->template->assign('warnings', $warnings);
    }

    private function validateForm(): void
    {
        // is the form submitted?
        if ($this->form->isSubmitted()) {
            // validate required fields
            $this->form->getField('site_title')->isFilled(BL::err('FieldIsRequired'));

            // date & time
            $this->form->getField('time_format')->isFilled(BL::err('FieldIsRequired'));
            $this->form->getField('date_format_short')->isFilled(BL::err('FieldIsRequired'));
            $this->form->getField('date_format_long')->isFilled(BL::err('FieldIsRequired'));

            // number
            $this->form->getField('number_format')->isFilled(BL::err('FieldIsRequired'));

            // domains filled in
            if ($this->form->getField('site_domains')->isFilled()) {
                // split on newlines
                $domains = explode("\n", trim($this->form->getField('site_domains')->getValue()));

                // loop domains
                foreach ($domains as $domain) {
                    // strip funky stuff
                    $domain = trim(str_replace(['www.', 'http://', 'https://'], '', $domain));

                    // invalid URL
                    if (!\SpoonFilter::isURL('http://' . $domain)) {
                        // set error
                        $this->form->getField('site_domains')->setError(BL::err('InvalidDomain'));

                        // stop looping domains
                        break;
                    }
                }
            }

            // no errors ?
            if ($this->form->isCorrect()) {
                // general settings
                $this->get('fork.settings')->set(
                    'Core',
                    'site_title_' . BL::getWorkingLanguage(),
                    $this->form->getField('site_title')->getValue()
                );
                $this->get('fork.settings')->set(
                    'Core',
                    'site_html_header',
                    $this->form->getField('site_html_header')->getValue()
                );
                $this->get('fork.settings')->set(
                    'Core',
                    'site_start_of_body_scripts',
                    $this->form->getField('site_start_of_body_scripts')->getValue()
                );
                $this->get('fork.settings')->set(
                    'Core',
                    'site_html_footer',
                    $this->form->getField('site_html_footer')->getValue()
                );

                // date & time formats
                $this->get('fork.settings')->set('Core', 'time_format', $this->form->getField('time_format')->getValue());
                $this->get('fork.settings')->set(
                    'Core',
                    'date_format_short',
                    $this->form->getField('date_format_short')->getValue()
                );
                $this->get('fork.settings')->set(
                    'Core',
                    'date_format_long',
                    $this->form->getField('date_format_long')->getValue()
                );

                // date & time formats
                $this->get('fork.settings')->set(
                    'Core',
                    'number_format',
                    $this->form->getField('number_format')->getValue()
                );

                // before we save the languages, we need to ensure that each language actually exists and may be chosen.
                $languages = [SITE_DEFAULT_LANGUAGE];
                $activeLanguages = array_unique(
                    array_merge($languages, $this->form->getField('active_languages')->getValue())
                );
                $redirectLanguages = array_unique(
                    array_merge($languages, $this->form->getField('redirect_languages')->getValue())
                );

                // cleanup redirect-languages, by removing the values that aren't present in the active languages
                $redirectLanguages = array_intersect($redirectLanguages, $activeLanguages);

                // save active languages
                $this->get('fork.settings')->set('Core', 'active_languages', $activeLanguages);
                $this->get('fork.settings')->set('Core', 'redirect_languages', $redirectLanguages);

                // domains may not contain www, http or https. Therefor we must loop and create the list of domains.
                $siteDomains = [];

                // domains filled in
                if ($this->form->getField('site_domains')->isFilled()) {
                    // split on newlines
                    $domains = explode("\n", trim($this->form->getField('site_domains')->getValue()));

                    // loop domains
                    foreach ($domains as $domain) {
                        // strip funky stuff
                        $siteDomains[] = trim(str_replace(['www.', 'http://', 'https://'], '', $domain));
                    }
                }

                // save domains
                $this->get('fork.settings')->set('Core', 'site_domains', $siteDomains);

                // assign report
                $this->template->assign('report', true);
                $this->template->assign('reportMessage', BL::msg('Saved'));
            }
        }
    }
}
