<?php

namespace ProgrammatorDev\FluentValidator\Translator;

use Composer\InstalledVersions;
use Symfony\Component\Translation\Loader\XliffFileLoader;
use Symfony\Contracts\Translation\TranslatorInterface;

readonly class Translator implements TranslatorInterface
{
    public function __construct(private string $locale) {}

    public function trans(string $id, array $parameters = [], ?string $domain = null, ?string $locale = null): string
    {
        // make sure we always have the correct path for the translation files
        $packagePath = InstalledVersions::getInstallPath('symfony/validator');
        $resourcePath = sprintf('%s/Resources/translations/validators.%s.xlf', $packagePath, $this->locale);

        $translator = new \Symfony\Component\Translation\Translator($this->locale);
        $translator->addLoader('xlf', new XliffFileLoader());
        $translator->addResource('xlf', $resourcePath, $this->locale);

        return $translator->trans($id, $parameters, $domain, $locale);
    }

    public function getLocale(): string
    {
        return $this->locale;
    }
}