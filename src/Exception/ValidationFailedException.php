<?php

namespace ProgrammatorDev\FluentValidator\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationFailedException extends \RuntimeException
{
    public function __construct(
        string $message,
        private readonly mixed $invalidValue,
        private readonly ConstraintViolationListInterface $violations,
    )
    {
        parent::__construct($message);
    }

    public function getInvalidValue(): mixed
    {
        return $this->invalidValue;
    }

    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }
}