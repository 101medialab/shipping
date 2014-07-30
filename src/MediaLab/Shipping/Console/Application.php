<?php

namespace MediaLab\Shipping\Console;

use Symfony\Component\Console\Application as BaseApplication;
use MediaLab\Shipping\Console\Command\BuildRegionsCommand;

class Application extends BaseApplication
{
    public function __construct()
    {
        parent::__construct('Shipping', '1.0');

        $this->addCommands(array(
            new BuildRegionsCommand()
        ));
    }
}
