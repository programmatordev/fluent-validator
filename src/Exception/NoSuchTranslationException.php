<?php

namespace ProgrammatorDev\FluentValidator\Exception;

class NoSuchTranslationException extends \RuntimeException
{
    public function __construct(string $locale)
    {
        $message = sprintf('Translation for locale "%s" was not found.', $locale);

        parent::__construct($message);
    }
}
