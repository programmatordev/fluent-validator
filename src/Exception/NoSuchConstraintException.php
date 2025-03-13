<?php

namespace ProgrammatorDev\FluentValidator\Exception;

class NoSuchConstraintException extends \RuntimeException
{
    public function __construct(string $constraintName)
    {
        $message = sprintf('Constraint "%s" was not found.', $constraintName);

        parent::__construct($message);
    }
}