<?php

namespace NameFinder\Checker;

class TwitterChecker
{
    private $guzzle;
    
    public function __construct()
    {
        $this->guzzle = new \GuzzleHttp\Client();
        
    }
    
    public function getKey()
    {
        return 'twitter';
    }
    
    public function getName()
    {
        return "Twitter";
    }
    
    public function check($name)
    {
        $response = $this->guzzle->request(
            'GET',
            'https://www.twitter.com/' . $name,
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
