<?php

namespace NameFinder\Checker;

use phpWhois\Whois;

class WhoisChecker
{
    private $tld;
    
    public function __construct($tld)
    {
        $this->tld = $tld;
        $this->whois = new Whois();
        $this->whois->deepWhois = false;
    }
    
    public function getKey()
    {
        return 'whois:' . $this->tld;
    }
    
    public function getName()
    {
        return "Whois " . $this->tld;
    }
    
    public function check($name)
    {
        $domain = $name . '.' . $this->tld;
        $result = $this->whois->lookup($domain, false);
        print_r($result);
        
        $res = 'TAKEN';
        if ($result['regrinfo']['registered'] == 'no') {
            $res = 'FREE';
        }
        if (isset($result['raw'])) {
            if ($result['raw'][0]==$domain . ' is free') {
                $res = 'FREE';
            }
        }
        return $res;
    }
}
