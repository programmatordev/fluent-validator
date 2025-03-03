<?php

namespace ProgrammatorDev\FluentValidator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Validation;

class FluentValidator
{
    /** @var Constraint[] */
    private array $constraints;

    private static function create(): self
    {
        return new self();
    }

    public static function __callStatic(string $constraintName, array $arguments = []): self
    {
        return self::create()->__call($constraintName, $arguments);
    }

    public function __call(string $constraintName, array $arguments = []): self
    {
        $constraintFactory = new ConstraintFactory();

        $this->addConstraint($constraintFactory->create($constraintName, $arguments));

        return $this;
    }

    public function validate(mixed $value): bool
    {
        $validator = Validation::createValidator();
        $constraintViolationList = $validator->validate($value, $this->constraints);

        dd($constraintViolationList);
    }

    public function getConstraints(): array
    {
        return $this->constraints;
    }

    public function addConstraint(Constraint $constraint): self
    {
        $this->constraints[] = $constraint;

        return $this;
    }
}