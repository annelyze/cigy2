<?php

namespace Frontend\Core\Engine;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use ForkCMS\App\KernelLoader;
use Symfony\Component\HttpKernel\KernelInterface;
use Frontend\Core\Engine\Navigation as FrontendNavigation;

/**
 * This class will be used to alter the footer-part of the HTML-document that will be created by the frontend.
 */
class Footer extends KernelLoader
{
    /**
     * TwigTemplate instance
     *
     * @var TwigTemplate
     */
    protected $template;

    /**
     * URL instance
     *
     * @var Url
     */
    protected $url;

    public function __construct(KernelInterface $kernel)
    {
        parent::__construct($kernel);

        $this->template = $this->getContainer()->get('templating');
        $this->url = $this->getContainer()->get('url');

        $this->getContainer()->set('footer', $this);
    }

    /**
     * Parse the footer into the template
     */
    public function parse(): void
    {
        $footerLinks = (array) Navigation::getFooterLinks();
        $this->template->assignGlobal('footerLinks', $footerLinks);

        $siteHTMLFooter = (string) $this->get('fork.settings')->get('Core', 'site_html_footer', null);

        // assign site wide html
        $this->template->assignGlobal('siteHTMLFooter', $siteHTMLFooter);
    }
}
