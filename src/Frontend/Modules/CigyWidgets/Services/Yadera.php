<?php

namespace Frontend\Modules\CigyWidgets\Services;

use Frontend\Modules\CigyWidgets\Engine\Model as FrontendCigyWidgetsModel;
use SpoonHTTP;
use DOMDocument;

/**
 * This is the Yadera service
 */
class Yadera
{
    // @later make these settings in Fork CMS backend
    const AUTH_USERNAME = 'rick.astley@wijs.be';
    const AUTH_PASSWORD = 'W8pqzcJzTO0a7FBzaK4PCOthk';
    const API_LOCATION = 'https://wijs.yadera.com/odata.svc/';
    const KEY_CLOSED_WON = 'RPQVJZ5C7TMCPHQ';
    const KEY_OFFERS = 'RP2PEC1MH7PBRB3';
    const KEY_TIMESHEET_ENTRIES = 'RPESG5JWYFTXSPP';
    const KEY_TIMESHEETS = 'RPB6DFPVW8XC3HJ';

    private function getHttpHeader(){
        $header = array();
        $header[] = 'Content-length: 0';
        $header[] = 'Content-type: application/json';

        return $header;
    }

    /**
     * @param int $team
     * @return string
     */
    public function getClosedWon(int $team = 0): string
    {
        // get team name
        $teamName = FrontendCigyWidgetsModel::getTeamName($team);

        // set URL and other appropriate options
        $url = self::API_LOCATION . self::KEY_CLOSED_WON;

        // set options
        $curlOptions = array(
            CURLOPT_HTTPHEADER => self::getHttpHeader(),
            CURLOPT_USERPWD => self::AUTH_USERNAME . ':' . self::AUTH_PASSWORD,
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC
        );


        $result = SpoonHTTP::getContent($url, $curlOptions);

        $doc = new DOMDocument();
        $doc->loadXML($result);
        $root = $doc->documentElement;
        $output = Yadera::domnode_to_array($root);
        $output['@root'] = $root->tagName;

echo '<pre>';var_dump($output);exit;

        // do call and get content
      //  $result = json_decode(SpoonHTTP::getContent($url, $curlOptions));

        return '';
    }

    // crappy internet code
    function domnode_to_array($node) {
        $output = array();
        switch ($node->nodeType) {
            case XML_CDATA_SECTION_NODE:
            case XML_TEXT_NODE:
                $output = trim($node->textContent);
                break;
            case XML_ELEMENT_NODE:
                for ($i=0, $m=$node->childNodes->length; $i<$m; $i++) {
                    $child = $node->childNodes->item($i);
                    $v = Yadera::domnode_to_array($child);
                    if(isset($child->tagName)) {
                        $t = $child->tagName;
                        if(!isset($output[$t])) {
                            $output[$t] = array();
                        }
                        $output[$t][] = $v;
                    }
                    elseif($v || $v === '0') {
                        $output = (string) $v;
                    }
                }
                if($node->attributes->length && !is_array($output)) { //Has attributes but isn't an array
                    $output = array('@content'=>$output); //Change output into an array.
                }
                /*if(is_array($output)) {
                    if($node->attributes->length) {
                        $a = array();
                        foreach($node->attributes as $attrName => $attrNode) {
                            $a[$attrName] = (string) $attrNode->value;
                        }
                        $output['@attributes'] = $a;
                    }
                    foreach ($output as $t => $v) {
                        if(is_array($v) && count($v)==1 && $t!='@attributes') {
                            $output[$t] = $v[0];
                        }
                    }
                }*/
                break;
        }
        return $output;
    }
}
