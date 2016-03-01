<?php

require "vendor/autoload.php";
use Validator\IPValidator as IPValidator;

class IPValidatorTest extends PHPUnit_Framework_TestCase {

    public function testIP(){

        $addresses = [
            'http://localhost' => false,
            'https://32.43.12.34' => true,
            'http://10.43.12.34' => false,
            'http://www.example.com' => true,
            'http://121.1.2.1' => true,
            'ftp://699.0.0.0' => false,
            'http://156.255.255.255' => false,
            'http://151.0.0.0' => false,
            'ftp://151.0.0.1' => true,
            'http://127.0.0.1' => false,
            'https://189.189.189.189' => true,
            'http://192.168.1.1' => false,
            'https://192.168.1.1' => false,
            'http://235.168.1.1' => false,
            'http://10.10.10.10' => false,
            'http://0.0.0.0' => false,
        ];

        foreach ($addresses as $host => $expected){
            PHPUnit_Framework_Assert::assertEquals($expected, IPValidator::isIncorrectIpAddress($host));
        }

    }

}