<?php

namespace ProgrammatorDev\FluentValidator;

use ProgrammatorDev\FluentValidator\Exception\InvalidConstraintException;
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
                    throw new InvalidConstraintException(
                        sprintf(
                            'Constraint "%s" must be an instance of "Symfony\Component\Validator\Constraint".',
                            $className
                        )
                    );
                }

                return $constraint;
            }
        }

        throw new NoSuchConstraintException(
            sprintf('Constraint "%s" was not found.', $constraintName)
        );
    }

    public function addNamespace(string $namespace): self
    {
        $this->namespaces[] = $namespace;

        return $this;
    }
}