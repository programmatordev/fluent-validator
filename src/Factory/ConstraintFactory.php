<?php

namespace ProgrammatorDev\FluentValidator\Factory;

use ProgrammatorDev\FluentValidator\Exception\NoSuchConstraintException;
use Symfony\Component\Validator\Constraint;

class ConstraintFactory
{
    /** @var string[] */
    private array $namespaces = ['Symfony\Component\Validator\Constraints'];

    public function create(string $constraintName, array $arguments = []): Constraint
    {
        $constraintName = ucfirst($constraintName);

        foreach ($this->namespaces as $namespace) {
            $class = sprintf('%s\%s', $namespace, $constraintName);

            // if class exists and is an instance of Constraint
            if (class_exists($class) && is_a($class, Constraint::class, true)) {
                return new $class(...$arguments);
            }
        }

        throw new NoSuchConstraintException($constraintName);
    }

    public function addNamespace(string $namespace): self
    {
        $this->namespaces[] = $namespace;

        return $this;
    }
}