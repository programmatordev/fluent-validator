<?php

namespace ProgrammatorDev\FluentValidator\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationFailedException extends \RuntimeException
{
    public function __construct(
        string $message,
        private readonly mixed $value,
        private readonly ConstraintViolationListInterface $violations,
    )
    {
        parent::__construct($message);
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }
}