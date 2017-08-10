<?php

namespace Frontend\Modules\CigyWidgets\Services;

use SpoonHTTP;

/**
 * This is the Hydi service
 */
class Hydi
{
    // @later make these settings in Fork CMS backend
    const API_LOCATION = 'https://hyd.timvermaercke.be/api/v1/';
    const API_LOGIN = 'annelies@timvermaercke.be';
    const API_PASSWORD = 'test';

    private function getHttpHeader(){
        $header = array();
        $header[] = 'Content-length: 0';
        $header[] = 'Content-type: application/json';

        return $header;
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        // set URL and other appropriate options
        $url = self::API_LOCATION . 'logout';

        // set options
        $curlOptions = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1
        );

        // do call and get content
        SpoonHTTP::getContent($url, $curlOptions);

         // set URL and other appropriate options
        $url = self::API_LOCATION . 'login';

        // set options
        $curlOptions = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => 'email=' . self::API_LOGIN . '&password=' . self::API_PASSWORD
        );

        // do call and get content
        $result = json_decode(SpoonHTTP::getContent($url, $curlOptions));

        if(isset($result->user) && isset($result->user->api_token)) return $result->user->api_token;
        return '';
    }

    /**
     * @param string $apiKey
     * @param int $team
     * @return string
     */
    public function getHydi($apiKey = '', $team = 0): string
    {
        // no API Key given
        if(empty($apiKey)) return '';

        // set URL and other appropriate options
        $url = self::API_LOCATION . 'submissions' . ($team = 0 ? '' : '?team=' . $team);

        // get header
        $header = self::getHttpHeader();
        $header[] = 'Authorization: ' . $apiKey;

        // set options
        $curlOptions = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTPHEADER => $header
        );

        // do call and get content
        $result = json_decode(SpoonHTTP::getContent($url, $curlOptions));

        if(isset($result->user) && isset($result->user->api_token)) return $result->user->api_token;
        return '';
    }
}
