<?php

namespace ProgrammatorDev\FluentValidator\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateInterfacesCommand extends Command
{
    public function __construct()
    {
        parent::__construct('interfaces:create');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        (new CreateStaticInterfaceCommand)->execute($input, $output);
        (new CreateChainedInterfaceCommand)->execute($input, $output);

        return Command::SUCCESS;
    }
}