<?php

namespace Validator;


class IPValidator
{
    /**
     * Checks url for local ip address, returns true if local url was found.
     * @param $url
     * @return bool
     */
    public static function isIncorrectIpAddress($url){
        // separate domain and protocol
        // https://www.example.com => [https, www.example.com]
        // https://189.189.189.189 => [https, 189.189.189.189]
        // http://189.189.189.189?action=test => [https, 189.189.189.189?action=test]
        list($protocol, $address) = explode('://', $url);

        if(strpos($address, 'localhost') !== false){
            return false;
        }
        //Special check cause of next will bw intval< and address like www.example.com.ua =>explode=>implode(intval) will be 0.0.0.0
        if($address == '0.0.0.0'){
            return false;
        }

        // Clear address from get body if it exists
        // www.example.com?action=test => www.example.com
        list($address) = empty(strpos($address,"?")) ? [$address] : explode('?',$address);

        $parts = array_map('intval', explode('.',$address));

        // If we have [www, example, com, us] => intval => [0,0,0,0] => implode => 0000
        // IP address has 4 parts [192, 168, 0, 1]
        // Laravel validator does not pass ip with more or less than 4 parts
        if(implode('',$parts) === '0000' || sizeof($parts) !== 4){
            return true;
        }

        // Check invalid ip parts
        // *.*.*.0 - subnet
        // *.*.*.255 - broadcast
        // 0.*.*.* - reserved
        // [224 - 239].*.*.* - reserved
        if(($parts[0] > 223  && $parts[0] < 240) || $parts[0] === 0 || $parts[0] > 254 || $parts[1] > 255 || $parts[2] > 255 || $parts[3] > 254 || $parts[3] == 0){
            return false;
        }

        //Check reserved ip parts
        switch ($parts[0]){
            // reserved 10.*.*.*
            case 10:
                return false;
            // reserved 127.*.*.*
            case 127:
                return false;
            // reserved 192.168.*.*
            case 192:
                if($parts[1] === 168){
                    return false;
                }
            // reserved 169.254.*.*
            case 169:
                if($parts[1] == 254){
                    return false;
                }
            // reserved 172.16.*.*
            case 172:
                if($parts[1] === 16){
                    return false;
                }
            // correct ip
            default:
                return true;
        }

    }
}