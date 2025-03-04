<?php

namespace ProgrammatorDev\FluentValidator;

use ProgrammatorDev\FluentValidator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolationListInterface;
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
        $constraint = $constraintFactory->create($constraintName, $arguments);

        $this->addConstraint($constraint);

        return $this;
    }

    private function runValidation(mixed $value): ConstraintViolationListInterface
    {
        $validator = Validation::createValidator();

        return $validator->validate($value, $this->constraints);
    }

    public function validate(mixed $value): ConstraintViolationListInterface
    {
        return $this->runValidation($value);
    }

    public function assert(mixed $value): void
    {
        $violations = $this->runValidation($value);

        if ($violations->count() > 0) {
            $message = $violations->get(0)->getMessage();
            throw new ValidationFailedException($message, $value, $violations);
        }
    }

    public function isValid(mixed $value): bool
    {
        $violations = $this->runValidation($value);

        return $violations->count() === 0;
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