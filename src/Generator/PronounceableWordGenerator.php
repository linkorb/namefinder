<?php

namespace NameFinder\Generator;

class PronounceableWordGenerator
{
    private $length;
    
    public function __construct($length = 6)
    {
        $this->length = $length;
        $container = new \PronounceableWord_DependencyInjectionContainer();
        $this->generator = $container->getGenerator();
    }
    
    public function getKey()
    {
        return 'pronounceableword:' . $this->length;
    }
    
    public function generate()
    {
        $name = $this->generator->generateWordOfGivenLength($this->length);
        return $name;
    }
}
