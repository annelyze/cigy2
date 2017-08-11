<?php

namespace Frontend\Modules\CigyWidgets\Widgets;

use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;

/**
 * This is the detail widget.
 */
class TipOfTheDay extends FrontendBaseWidget
{
    public function execute(): void
    {
        parent::execute();
        $this->loadTemplate();

        $tips = array(
            "Contacteer de klanten die net geen promoter zijn met de vraag wat je kan doen om hun de score te laten herzien, en stuur erna opnieuw een bevraging.",
            "Contacteer klanten die critics zijn met de vraag wat je kan doen om hun de score te laten herzien, en stuur erna opnieuw een bevraging.",
            "Probeer bij iedere mindere score een duidelijke uitleg te krijgen.\nZie je een bepaalde trend?\nKan je dit structureel aanpakken?",
            "Zijn er facturen die al langer dan drie maand openstaan?\nHet contacteren van deze klanten met de vraag waarom dit zo is kan helpen.",
            "Ruimte in de planning?\nCheck even bij het management of er interne projecten zijn die je ABR kunnen opkrikken.",
            "Ruimte in de planning?\nWat tijd investeren in een proactieve pitch voor een product bij een bestaande klant kan je closed wons helpen.",
            "Laag aantal pitches of moeite om de deals verkocht te krijgen?\nHet analyseren van de reden van lagere closed wons zal je helpen oplossingen te definiëren.",
            "Is er een trend merkbaar bij de promoters?\nHoe heb je dit bereikt?\nDeel dit met de andere teams!",
        );

        $this->template->assign('widgetTip', $tips[array_rand($tips)]);
    }

}
