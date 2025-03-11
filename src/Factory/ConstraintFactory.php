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
            $className = sprintf('%s\%s', $namespace, $constraintName);

            if (class_exists($className)) {
                $constraint = new $className(...$arguments);

                if (!$constraint instanceof Constraint) {
                    throw new NoSuchConstraintException($constraintName);
                }

                return $constraint;
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