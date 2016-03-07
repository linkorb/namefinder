<?php

namespace NameFinder\Checker;

class ResolveChecker
{
    private $tld;
    private $prefix;
    
    public function __construct($tld)
    {
        $this->tld = $tld;
    }
    
    public function getKey()
    {
        return 'resolve:' . $this->tld;
    }
    
    public function getName()
    {
        return "Resolving " . trim($this->tld);
    }
    
    public function check($name)
    {
        $domain = $name . '.' . $this->tld;
        
        // Try to resolve the apex domain
        $ip = gethostbyname($domain);
        if ($ip != $domain) {
            return 'TAKEN';
        }

        // Try to resolve the www subdomain
        $ip = gethostbyname('www.' . $domain);
        if ($ip != 'www.' . $domain) {
            return 'TAKEN';
        }

        // Try to resolve a nameserver
        $records = dns_get_record($domain, DNS_NS, $nameservers);
        if (count($records)>0) {
            return 'TAKEN';
        }

        // Try to resolve mx records
        $records = dns_get_record($domain, DNS_MX, $nameservers);
        if (count($records)>0) {
            return 'TAKEN';
        }
                
        return 'FREE';
    }
}
