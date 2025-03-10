<?php

namespace ProgrammatorDev\FluentValidator;

use ProgrammatorDev\FluentValidator\Exception\ValidationFailedException;
use ProgrammatorDev\FluentValidator\Factory\ConstraintFactory;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Contracts\Translation\TranslatorInterface;

/** @mixin StaticInterface */
class FluentValidator
{
    /** @var Constraint[] */
    private array $constraints;

    /** @var string[] */
    private static array $namespaces = [];

    private static ?TranslatorInterface $translator = null;

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

        foreach (self::$namespaces as $namespace) {
            $constraintFactory->addNamespace($namespace);
        }

        $constraint = $constraintFactory->create($constraintName, $arguments);
        $this->addConstraint($constraint);

        return $this;
    }

    private function runValidation(
        mixed $value,
        ?string $name = null,
        array|null|string|GroupSequence $groups = null
    ): ConstraintViolationListInterface
    {
        $builder = Validation::createValidatorBuilder();

        if (self::$translator !== null) {
            $builder->setTranslator(self::$translator);
        }

        $context = $builder->getValidator()->startContext();

        if ($name !== null) {
            $context->atPath($name);
        }

        $context->validate($value, $this->constraints, $groups);

        return $context->getViolations();
    }

    public function validate(
        mixed $value,
        ?string $name = null,
        string|GroupSequence|array|null $groups = null
    ): ConstraintViolationListInterface
    {
        return $this->runValidation($value, $name, $groups);
    }

    public function assert(
        mixed $value,
        ?string $name = null,
        string|GroupSequence|array|null $groups = null
    ): void
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

    public function isValid(
        mixed $value,
        string|GroupSequence|array|null $groups = null
    ): bool
    {
        $violations = $this->runValidation($value, groups: $groups);

        return $violations->count() === 0;
    }

    public function getConstraints(): array
    {
        return $this->constraints;
    }

    private function addConstraint(Constraint $constraint): void
    {
        $this->constraints[] = $constraint;
    }

    public static function addNamespace(string $namespace): void
    {
        self::$namespaces[] = $namespace;
    }

    public static function setTranslator(?TranslatorInterface $translator): void
    {
        self::$translator = $translator;
    }
}