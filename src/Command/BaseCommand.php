<?php

namespace NameFinder\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
use NameFinder\Repository\PdoNameRepository;
use NameFinder\Repository\PdoNameCheckRepository;
use PDO;

class BaseCommand extends Command
{
    protected $pdo;
    protected $nameRepository;
    protected $config;
    
    public function __construct()
    {
        $filename = __DIR__ . '/../../config.yml';
        $this->config = Yaml::parse(file_get_contents($filename));
        
        $url = $this->config['pdo'];
        $scheme = parse_url($url, PHP_URL_SCHEME);
        $user = parse_url($url, PHP_URL_USER);
        $pass = parse_url($url, PHP_URL_PASS);
        $host = parse_url($url, PHP_URL_HOST);
        $port = parse_url($url, PHP_URL_PORT);
        $dbname = parse_url($url, PHP_URL_PATH);
        if (!$port) {
            $port = 3306;
        }
        $dsn = sprintf(
            '%s:dbname=%s;host=%s;port=%d',
            $scheme,
            substr($dbname, 1),
            $host,
            $port
        );
        //echo $dsn;exit();
        $this->pdo = new PDO($dsn, $user, $pass);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $this->nameRepository = new PdoNameRepository($this->pdo);
        $this->nameCheckRepository = new PdoNameCheckRepository($this->pdo);
        
        parent::__construct();
    }
}
