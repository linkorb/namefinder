<?php

namespace NameFinder\Checker;

class InstagramChecker
{
    private $guzzle;
    
    public function __construct()
    {
        $this->guzzle = new \GuzzleHttp\Client();
        
    }
    
    public function getKey()
    {
        return 'instagram';
    }
    
    public function getName()
    {
        return "Instagram";
    }
    
    public function check($name)
    {
        $response = $this->guzzle->request(
            'GET',
            'https://www.instagram.com/' . $name,
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
