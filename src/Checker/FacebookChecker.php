<?php

namespace NameFinder\Checker;

class FacebookChecker
{
    private $guzzle;
    
    public function __construct()
    {
        $this->guzzle = new \GuzzleHttp\Client();
        
    }
    
    public function getKey()
    {
        return 'facebook';
    }
    
    public function getName()
    {
        return "Facebook";
    }
    
    public function check($name)
    {
        $response = $this->guzzle->request(
            'GET',
            'https://www.facebook.com/' . $name,
            array('exceptions' => false)
        );
        //echo "FB HTTP STATUS: " . $response->getStatusCode() . "\n";

        $res = 'TAKEN';
        if ($response->getStatusCode()==404) {
            $res = 'FREE';
        }
        return $res;
    }
}
