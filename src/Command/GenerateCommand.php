<?php

namespace NameFinder\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use NameFinder\Generator\PronounceableWordGenerator;
use NameFinder\Checker\ResolveChecker;
use NameFinder\Checker\NsChecker;
use NameFinder\Checker\FacebookChecker;
use NameFinder\Checker\TwitterChecker;
use NameFinder\Checker\InstagramChecker;
use NameFinder\Checker\GitHubChecker;
use NameFinder\Checker\WhoisChecker;

class GenerateCommand extends BaseCommand
{
    protected $checkers = array();

    protected function configure()
    {
        $this
            ->setName('generate')
            ->setDescription(
                'Generates names'
            )
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

        $generator = new PronounceableWordGenerator(5);
        $checker = new ResolveChecker('com');
        $this->checkers[] = $checker;
        $checker = new ResolveChecker('nl');
        $this->checkers[] = $checker;

        /*
        $checker = new WhoisChecker('com');
        $this->checkers[] = $checker;
        $checker = new WhoisChecker('nl');
        $this->checkers[] = $checker;
        */

        $checker = new FacebookChecker();
        $this->checkers[] = $checker;
        $checker = new TwitterChecker();
        $this->checkers[] = $checker;
        $checker = new InstagramChecker();
        $this->checkers[] = $checker;
        $checker = new GitHubChecker();
        $this->checkers[] = $checker;
        
        for ($i=0; $i<100; $i++) {
            $name = $generator->generate();
            if (!$this->nameRepository->exists($name)) {
                $output->writeLn("Registering: <comment>$name</comment>");
                
                $this->nameRepository->register($name, $generator->getKey());
                
                $status = 'FREE';
                foreach ($this->checkers as $checker) {
                    if (!$this->nameCheckRepository->exists($name, $checker->getKey())) {
                        $output->write(" - Checker: " . $checker->getName() . " ");
                        $status = $checker->check($name);
                        if ($status == 'FREE') {
                            $output->write("<info>$status</info>");
                        } else {
                            $output->write("<error>$status</error>");
                        }
                        $output->writeLn('');
                        $this->nameCheckRepository->register($name, $checker->getKey(), $status);
                    }
                }
                if ($status == 'FREE') {
                    $output->writeLn("<fg=green>$name is FREE</>");
                }
            }
        }

    }
}
