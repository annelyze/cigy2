<?php

namespace Frontend\Modules\CigyWidgets\Widgets;

use Frontend\Core\Engine\Base\Widget as FrontendBaseWidget;
use Frontend\Modules\CigyWidgets\Engine\Model as FrontendCigyWidgetsModel;

/**
 * This is the detail widget.
 */
class Nps extends FrontendBaseWidget
{
    public $customerguageApiLocation = 'https://api.customergauge.com/v4.1/'; 
    
    public $customerGaugeApiKey = 'sgMBH2x9gKuwkpbfTemzBYQdMSrMya00rNB8P9h6UMCfcNu52RFEQwSCojpWtqWU';

    public function execute(): void
    {
        parent::execute();
        $this->loadTemplate();

        $teamNpsDetail = $this->getNpsDetail(FrontendCigyWidgetsModel::getTeamName($this->pageTeamFilter));
        
        $companyNpsDetail = $this->getCompanyNpsDetail();
        $npsData = array(
            'team_name' => FrontendCigyWidgetsModel::getTeamName($this->pageTeamFilter),
            'team' => $teamNpsDetail->Data->nps,
            'company' => $companyNpsDetail->Data->nps,
            'target' => '9',
        );
        $this->template->assign('widgetNps', $npsData);
    }

    public function getHttpHeader(){
        $header = array();
        $header[] = 'Content-length: 0';
        $header[] = 'Content-type: application/json';
        $header[] = 'Authorization: api_key ' . $this->customerGaugeApiKey;

        return $header;
    }

    private function getCompanyNpsDetail(){
        return $this->getNpsDetail('');
    }

    private function getNpsDetail($team){
        $curl = curl_init();
        // set URL and other appropriate options
        $url = $this->customerguageApiLocation . 'report/nps.json?date_range_from=' . date('Y') . '-01-01';
        if($team != '')
        {
            $url = $url.'&filter_SegmentB=' . $team . '';
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->getHttpHeader());
        
        $npsDetail = json_decode(curl_exec($curl));

        // close cURL resource, and free up system resources
        curl_close($curl);
        
        return $npsDetail;
    }
}
