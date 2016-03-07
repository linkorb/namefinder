<?php

namespace NameFinder\Checker;

class GitHubChecker
{
    private $guzzle;
    
    public function __construct()
    {
        $this->guzzle = new \GuzzleHttp\Client();
        
    }
    
    public function getKey()
    {
        return 'github';
    }
    
    public function getName()
    {
        return "GitHub";
    }
    
    public function check($name)
    {
        $response = $this->guzzle->request(
            'GET',
            'https://www.github.com/' . $name,
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
