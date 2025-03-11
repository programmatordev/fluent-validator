<?php

namespace ProgrammatorDev\FluentValidator\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateValidatorInterfacesCommand extends Command
{
    public function __construct()
    {
        parent::__construct('validator:interfaces:create');
    }

    /**
     * @throws \ReflectionException
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        (new CreateStaticValidatorInterfaceCommand)->execute($input, $output);
        (new CreateChainedValidatorInterfaceCommand)->execute($input, $output);

        return Command::SUCCESS;
    }
}