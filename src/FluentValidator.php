<?php

namespace ProgrammatorDev\FluentValidator;

use ProgrammatorDev\FluentValidator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GroupSequence;
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

    private function runValidation(mixed $value, ?string $name = null, array|null|string|GroupSequence $groups = null): ConstraintViolationListInterface
    {
        $validator = Validation::createValidatorBuilder()
            ->getValidator()
            ->startContext();

        if ($name !== null) {
            $validator = $validator->atPath($name);
        }

        $validator = $validator->validate($value, $this->constraints, $groups);

        return $validator->getViolations();
    }

    public function validate(mixed $value, ?string $name = null, null|array|string|GroupSequence $groups = null): ConstraintViolationListInterface
    {
        return $this->runValidation($value, $name, $groups);
    }

    public function assert(mixed $value, ?string $name = null, null|array|string|GroupSequence $groups = null): void
    {
        $violations = $this->runValidation($value, $name, $groups);

        if ($violations->count() > 0) {
            $message = $violations->get(0)->getMessage();

            if ($name !== null) {
                $message = sprintf('%s: %s', $name, $message);
            }

            throw new ValidationFailedException($message, $value, $violations);
        }
    }

    public function isValid(mixed $value, null|array|string|GroupSequence $groups = null): bool
    {
        $violations = $this->runValidation($value, groups: $groups);

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