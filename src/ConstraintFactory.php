<?php

namespace ProgrammatorDev\FluentValidator;

use Symfony\Component\Validator\Constraint;

class ConstraintFactory
{
    private array $namespaces = ['Symfony\\Component\\Validator\\Constraints'];

    public function create(string $constraintName, array $arguments = []): Constraint
    {
        foreach ($this->namespaces as $namespace) {
            $className = sprintf('%s\\%s', $namespace, ucfirst($constraintName));

            if (class_exists($className)) {
                return new $className(...$arguments);
            }
        }

        throw new \Exception(
            sprintf('"%s" constraint does not exist.', $constraintName)
        );
    }
}