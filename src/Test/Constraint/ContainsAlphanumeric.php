<?php

namespace ProgrammatorDev\FluentValidator\Test\Constraint;

use Symfony\Component\Validator\Constraint;

// test taken from the official Symfony Validator documentation
// https://symfony.com/doc/current/validation/custom_constraint.html
class ContainsAlphanumeric extends Constraint
{
    public string $message = 'The string "{{ string }}" contains an illegal character: it can only contain letters or numbers.';
    public string $mode = 'strict';

    // all configurable options must be passed to the constructor
    public function __construct(?string $mode = null, ?string $message = null, ?array $groups = null, $payload = null)
    {
        parent::__construct([], $groups, $payload);

        $this->mode = $mode ?? $this->mode;
        $this->message = $message ?? $this->message;
    }

    public function __sleep(): array
    {
        return array_merge(parent::__sleep(), ['mode']);
    }
}