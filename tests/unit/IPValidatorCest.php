<?php

class IPValidatorCest
{

    protected $addresses;

    public function _before(UnitTester $I)
    {
        $this->addresses = $this->_getAddresses();
    }

    public function _after(UnitTester $I)
    {
    }

    // tests
    public function tryToTest(UnitTester $I)
    {
        foreach ($this->addresses as $host => $expected){
            require_once (realpath(__DIR__ . DIRECTORY_SEPARATOR . '../../')."/validator/IPValidator.php");
            foreach($this->addresses as $host => $expected){
                $I->assertEquals($expected, \Validator\IPValidator::isIncorrectIpAddress($host));
            }
        }
    }

    private function _getAddresses(){
        return [
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
    }
}
