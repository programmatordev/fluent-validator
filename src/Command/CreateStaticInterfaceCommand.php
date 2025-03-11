<?php

namespace ProgrammatorDev\FluentValidator\Command;

use Composer\InstalledVersions;
use ProgrammatorDev\FluentValidator\Writer\InterfaceWriter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Constraint;
use WyriHaximus\Lister;

class CreateStaticInterfaceCommand extends Command
{
    public function __construct()
    {
        parent::__construct('validator:static-interface:create');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $packagePath = InstalledVersions::getInstallPath('symfony/validator');
        $constraintsPath = sprintf('%s/Constraints', $packagePath);
        $classes = Lister::instantiatableClassesInDirectory($constraintsPath);

        $file = new InterfaceWriter('StaticValidatorInterface');
        $file->writeInterfaceStart();

        foreach ($classes as $class) {
            // only handle classes that are instances of Constraint
            if (!is_a($class, Constraint::class, true)) {
                continue;
            }

            $constraintReflection = new \ReflectionClass($class);
            $constraintConstructor = $constraintReflection->getConstructor();

            $methodName = lcfirst($constraintReflection->getShortName());
            $methodParameters = $constraintConstructor->getParameters();

            $file->writeMethod($methodName, $methodParameters, true);
        }

        $file->writeInterfaceEnd();

        return Command::SUCCESS;
    }
}