<?php

namespace Frontend\Modules\CigyWidgets\Services;

use Frontend\Modules\CigyWidgets\Engine\Model as FrontendCigyWidgetsModel;
use SpoonHTTP;

/**
 * This is the CustomerGauge service
 */
class CustomerGauge
{
    // @later make these settings in Fork CMS backend
    const API_LOCATION = 'https://api.customergauge.com/v4.1/';
    const API_KEY = 'sgMBH2x9gKuwkpbfTemzBYQdMSrMya00rNB8P9h6UMCfcNu52RFEQwSCojpWtqWU';

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

    private function getHttpHeader(){
        $header = array();
        $header[] = 'Content-length: 0';
        $header[] = 'Content-type: application/json';
        $header[] = 'Authorization: api_key ' . self::API_KEY;

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
        curl_setopt($curl, CURLOPT_HTTPHEADER, self::getHttpHeader());

        $npsDetail = json_decode(curl_exec($curl));

        // close cURL resource, and free up system resources
        curl_close($curl);

        return $npsDetail;
    }

    /**
     * @param int $team
     * @return string
     */
    public function getNps(int $team = 0): string
    {
        // get team name
        $teamName = FrontendCigyWidgetsModel::getTeamName($team);

        // set URL and other appropriate options
        $url = self::API_LOCATION . 'report/nps.json?date_range_from=' . date('Y') . '-01-01';
        if($team != '')
        {
            $url = $url.'&filter_SegmentB=' . $teamName . '';
        }

        // set options
        $curlOptions = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTPHEADER => self::getHttpHeader()
        );

        // do call and get content
        $result = json_decode(SpoonHTTP::getContent($url, $curlOptions));

        return $result->Data->nps;
    }
}
