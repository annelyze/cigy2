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

    private function getHttpHeader(){
        $header = array();
        $header[] = 'Content-length: 0';
        $header[] = 'Content-type: application/json';
        $header[] = 'Authorization: api_key ' . self::API_KEY;

        return $header;
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
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTPHEADER => self::getHttpHeader()
        );

        // do call and get content
        $result = json_decode(SpoonHTTP::getContent($url, $curlOptions));

        return $result->Data->nps;
    }
}
