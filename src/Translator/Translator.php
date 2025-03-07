<?php

namespace ProgrammatorDev\FluentValidator\Translator;

use Composer\InstalledVersions;
use Symfony\Component\Translation\Loader\XliffFileLoader;
use Symfony\Contracts\Translation\TranslatorInterface;

readonly class Translator implements TranslatorInterface
{
    private \Symfony\Component\Translation\Translator $translator;

    public function __construct(private string $locale)
    {
        // make sure we always have the correct path for the translation files
        $packagePath = InstalledVersions::getInstallPath('symfony/validator');
        $resourcePath = sprintf('%s/Resources/translations/validators.%s.xlf', $packagePath, $this->locale);

        $this->translator = new \Symfony\Component\Translation\Translator($this->locale);
        $this->translator->addLoader('xlf', new XliffFileLoader());
        $this->translator->addResource('xlf', $resourcePath, $this->locale);
    }

    public function trans(string $id, array $parameters = [], ?string $domain = null, ?string $locale = null): string
    {
        return $this->translator->trans($id, $parameters, $domain, $locale);
    }

    public function getLocale(): string
    {
        return $this->locale;
    }
}