<?php

require_once __DIR__ . '/vendor/autoload.php';

use RstTableToolBundle\Command\GenerateRstTableCommand;
use Symfony\Component\Console\Application;

$console = new Application();

$console->add(new GenerateRstTableCommand());

$console->run();