<?php

namespace ProgrammatorDev\FluentValidator;

interface ChainedInterface
{
    public function all(
        mixed $constraints = null,
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function atLeastOneOf(
        mixed $constraints = null,
        ?array $groups = null,
        mixed $payload = null,
        ?string $message = null,
        ?string $messageCollection = null,
        ?bool $includeInternalMessages = null,
    ): ChainedInterface;

    public function bic(
        ?array $options = null,
        ?string $message = null,
        ?string $iban = null,
        ?string $ibanPropertyPath = null,
        ?string $ibanMessage = null,
        ?array $groups = null,
        mixed $payload = null,
        ?string $mode = null,
    ): ChainedInterface;

    public function blank(
        ?array $options = null,
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function callback(
        callable|array|string|null $callback = null,
        ?array $groups = null,
        mixed $payload = null,
        array $options = [],
    ): ChainedInterface;

    public function cardScheme(
        array|string|null $schemes,
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
        array $options = [],
    ): ChainedInterface;

    public function cascade(
        array|string|null $exclude = null,
        ?array $options = null,
    ): ChainedInterface;

    public function charset(
        array|string $encodings = [],
        string $message = 'The detected character encoding is invalid ({{ detected }}). Allowed encodings are {{ encodings }}.',
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function choice(
        array|string $options = [],
        ?array $choices = null,
        callable|string|null $callback = null,
        ?bool $multiple = null,
        ?bool $strict = null,
        ?int $min = null,
        ?int $max = null,
        ?string $message = null,
        ?string $multipleMessage = null,
        ?string $minMessage = null,
        ?string $maxMessage = null,
        ?array $groups = null,
        mixed $payload = null,
        ?bool $match = null,
    ): ChainedInterface;

    public function cidr(
        ?array $options = null,
        ?string $version = null,
        ?int $netmaskMin = null,
        ?int $netmaskMax = null,
        ?string $message = null,
        ?array $groups = null,
         $payload = null,
        ?callable $normalizer = null,
    ): ChainedInterface;

    public function collection(
        mixed $fields = null,
        ?array $groups = null,
        mixed $payload = null,
        ?bool $allowExtraFields = null,
        ?bool $allowMissingFields = null,
        ?string $extraFieldsMessage = null,
        ?string $missingFieldsMessage = null,
    ): ChainedInterface;

    public function count(
        array|int|null $exactly = null,
        ?int $min = null,
        ?int $max = null,
        ?int $divisibleBy = null,
        ?string $exactMessage = null,
        ?string $minMessage = null,
        ?string $maxMessage = null,
        ?string $divisibleByMessage = null,
        ?array $groups = null,
        mixed $payload = null,
        array $options = [],
    ): ChainedInterface;

    public function country(
        ?array $options = null,
        ?string $message = null,
        ?bool $alpha3 = null,
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function cssColor(
        array|string $formats = [],
        ?string $message = null,
        ?array $groups = null,
         $payload = null,
        ?array $options = null,
    ): ChainedInterface;

    public function currency(
        ?array $options = null,
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function date(
        ?array $options = null,
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function dateTime(
        array|string|null $format = null,
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
        array $options = [],
    ): ChainedInterface;

    public function disableAutoMapping(
        ?array $options = null,
    ): ChainedInterface;

    public function divisibleBy(
        mixed $value = null,
        ?string $propertyPath = null,
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
        array $options = [],
    ): ChainedInterface;

    public function email(
        ?array $options = null,
        ?string $message = null,
        ?string $mode = null,
        ?callable $normalizer = null,
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function enableAutoMapping(
        ?array $options = null,
    ): ChainedInterface;

    public function equalTo(
        mixed $value = null,
        ?string $propertyPath = null,
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
        array $options = [],
    ): ChainedInterface;

    public function expression(
        \Symfony\Component\ExpressionLanguage\Expression|array|string|null $expression,
        ?string $message = null,
        ?array $values = null,
        ?array $groups = null,
        mixed $payload = null,
        array $options = [],
        ?bool $negate = null,
    ): ChainedInterface;

    public function expressionSyntax(
        ?array $options = null,
        ?string $message = null,
        ?string $service = null,
        ?array $allowedVariables = null,
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function file(
        ?array $options = null,
        string|int|null $maxSize = null,
        ?bool $binaryFormat = null,
        array|string|null $mimeTypes = null,
        ?int $filenameMaxLength = null,
        ?string $notFoundMessage = null,
        ?string $notReadableMessage = null,
        ?string $maxSizeMessage = null,
        ?string $mimeTypesMessage = null,
        ?string $disallowEmptyMessage = null,
        ?string $filenameTooLongMessage = null,
        ?string $uploadIniSizeErrorMessage = null,
        ?string $uploadFormSizeErrorMessage = null,
        ?string $uploadPartialErrorMessage = null,
        ?string $uploadNoFileErrorMessage = null,
        ?string $uploadNoTmpDirErrorMessage = null,
        ?string $uploadCantWriteErrorMessage = null,
        ?string $uploadExtensionErrorMessage = null,
        ?string $uploadErrorMessage = null,
        ?array $groups = null,
        mixed $payload = null,
        array|string|null $extensions = null,
        ?string $extensionsMessage = null,
    ): ChainedInterface;

    public function greaterThan(
        mixed $value = null,
        ?string $propertyPath = null,
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
        array $options = [],
    ): ChainedInterface;

    public function greaterThanOrEqual(
        mixed $value = null,
        ?string $propertyPath = null,
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
        array $options = [],
    ): ChainedInterface;

    public function hostname(
        ?array $options = null,
        ?string $message = null,
        ?bool $requireTld = null,
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function iban(
        ?array $options = null,
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function identicalTo(
        mixed $value = null,
        ?string $propertyPath = null,
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
        array $options = [],
    ): ChainedInterface;

    public function image(
        ?array $options = null,
        string|int|null $maxSize = null,
        ?bool $binaryFormat = null,
        ?array $mimeTypes = null,
        ?int $filenameMaxLength = null,
        ?int $minWidth = null,
        ?int $maxWidth = null,
        ?int $maxHeight = null,
        ?int $minHeight = null,
        int|float|null $maxRatio = null,
        int|float|null $minRatio = null,
        int|float|null $minPixels = null,
        int|float|null $maxPixels = null,
        ?bool $allowSquare = null,
        ?bool $allowLandscape = null,
        ?bool $allowPortrait = null,
        ?bool $detectCorrupted = null,
        ?string $notFoundMessage = null,
        ?string $notReadableMessage = null,
        ?string $maxSizeMessage = null,
        ?string $mimeTypesMessage = null,
        ?string $disallowEmptyMessage = null,
        ?string $filenameTooLongMessage = null,
        ?string $uploadIniSizeErrorMessage = null,
        ?string $uploadFormSizeErrorMessage = null,
        ?string $uploadPartialErrorMessage = null,
        ?string $uploadNoFileErrorMessage = null,
        ?string $uploadNoTmpDirErrorMessage = null,
        ?string $uploadCantWriteErrorMessage = null,
        ?string $uploadExtensionErrorMessage = null,
        ?string $uploadErrorMessage = null,
        ?string $sizeNotDetectedMessage = null,
        ?string $maxWidthMessage = null,
        ?string $minWidthMessage = null,
        ?string $maxHeightMessage = null,
        ?string $minHeightMessage = null,
        ?string $minPixelsMessage = null,
        ?string $maxPixelsMessage = null,
        ?string $maxRatioMessage = null,
        ?string $minRatioMessage = null,
        ?string $allowSquareMessage = null,
        ?string $allowLandscapeMessage = null,
        ?string $allowPortraitMessage = null,
        ?string $corruptedMessage = null,
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function ip(
        ?array $options = null,
        ?string $version = null,
        ?string $message = null,
        ?callable $normalizer = null,
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function isFalse(
        ?array $options = null,
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function isNull(
        ?array $options = null,
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function isTrue(
        ?array $options = null,
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function isbn(
        array|string|null $type = null,
        ?string $message = null,
        ?string $isbn10Message = null,
        ?string $isbn13Message = null,
        ?string $bothIsbnMessage = null,
        ?array $groups = null,
        mixed $payload = null,
        array $options = [],
    ): ChainedInterface;

    public function isin(
        ?array $options = null,
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function issn(
        ?array $options = null,
        ?string $message = null,
        ?bool $caseSensitive = null,
        ?bool $requireHyphen = null,
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function json(
        ?array $options = null,
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function language(
        ?array $options = null,
        ?string $message = null,
        ?bool $alpha3 = null,
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function length(
        array|int|null $exactly = null,
        ?int $min = null,
        ?int $max = null,
        ?string $charset = null,
        ?callable $normalizer = null,
        ?string $countUnit = null,
        ?string $exactMessage = null,
        ?string $minMessage = null,
        ?string $maxMessage = null,
        ?string $charsetMessage = null,
        ?array $groups = null,
        mixed $payload = null,
        array $options = [],
    ): ChainedInterface;

    public function lessThan(
        mixed $value = null,
        ?string $propertyPath = null,
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
        array $options = [],
    ): ChainedInterface;

    public function lessThanOrEqual(
        mixed $value = null,
        ?string $propertyPath = null,
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
        array $options = [],
    ): ChainedInterface;

    public function locale(
        ?array $options = null,
        ?string $message = null,
        ?bool $canonicalize = null,
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function luhn(
        ?array $options = null,
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function macAddress(
        string $message = 'This value is not a valid MAC address.',
        string $type = 'all',
        ?callable $normalizer = null,
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function negative(
        ?array $options = null,
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function negativeOrZero(
        ?array $options = null,
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function noSuspiciousCharacters(
        ?array $options = null,
        ?string $restrictionLevelMessage = null,
        ?string $invisibleMessage = null,
        ?string $mixedNumbersMessage = null,
        ?string $hiddenOverlayMessage = null,
        ?int $checks = null,
        ?int $restrictionLevel = null,
        ?array $locales = null,
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function notBlank(
        ?array $options = null,
        ?string $message = null,
        ?bool $allowNull = null,
        ?callable $normalizer = null,
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function notCompromisedPassword(
        ?array $options = null,
        ?string $message = null,
        ?int $threshold = null,
        ?bool $skipOnError = null,
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function notEqualTo(
        mixed $value = null,
        ?string $propertyPath = null,
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
        array $options = [],
    ): ChainedInterface;

    public function notIdenticalTo(
        mixed $value = null,
        ?string $propertyPath = null,
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
        array $options = [],
    ): ChainedInterface;

    public function notNull(
        ?array $options = null,
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function optional(
        mixed $options = null,
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function passwordStrength(
        ?array $options = null,
        ?int $minScore = null,
        ?array $groups = null,
        mixed $payload = null,
        ?string $message = null,
    ): ChainedInterface;

    public function positive(
        ?array $options = null,
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function positiveOrZero(
        ?array $options = null,
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function range(
        ?array $options = null,
        ?string $notInRangeMessage = null,
        ?string $minMessage = null,
        ?string $maxMessage = null,
        ?string $invalidMessage = null,
        ?string $invalidDateTimeMessage = null,
        mixed $min = null,
        ?string $minPropertyPath = null,
        mixed $max = null,
        ?string $maxPropertyPath = null,
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function regex(
        array|string|null $pattern,
        ?string $message = null,
        ?string $htmlPattern = null,
        ?bool $match = null,
        ?callable $normalizer = null,
        ?array $groups = null,
        mixed $payload = null,
        array $options = [],
    ): ChainedInterface;

    public function required(
        mixed $options = null,
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function sequentially(
        mixed $constraints = null,
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function time(
        ?array $options = null,
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
        ?bool $withSeconds = null,
    ): ChainedInterface;

    public function timezone(
        array|int|null $zone = null,
        ?string $message = null,
        ?string $countryCode = null,
        ?bool $intlCompatible = null,
        ?array $groups = null,
        mixed $payload = null,
        array $options = [],
    ): ChainedInterface;

    public function traverse(
        array|bool|null $traverse = null,
    ): ChainedInterface;

    public function type(
        array|string|null $type,
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
        array $options = [],
    ): ChainedInterface;

    public function ulid(
        ?array $options = null,
        ?string $message = null,
        ?array $groups = null,
        mixed $payload = null,
        ?string $format = null,
    ): ChainedInterface;

    public function unique(
        ?array $options = null,
        ?string $message = null,
        ?callable $normalizer = null,
        ?array $groups = null,
        mixed $payload = null,
        array|string|null $fields = null,
        ?string $errorPath = null,
    ): ChainedInterface;

    public function url(
        ?array $options = null,
        ?string $message = null,
        ?array $protocols = null,
        ?bool $relativeProtocol = null,
        ?callable $normalizer = null,
        ?array $groups = null,
        mixed $payload = null,
        ?bool $requireTld = null,
        ?string $tldMessage = null,
    ): ChainedInterface;

    public function uuid(
        ?array $options = null,
        ?string $message = null,
        array|int|null $versions = null,
        ?bool $strict = null,
        ?callable $normalizer = null,
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function valid(
        ?array $options = null,
        ?array $groups = null,
         $payload = null,
        ?bool $traverse = null,
    ): ChainedInterface;

    public function week(
        ?string $min = null,
        ?string $max = null,
        string $invalidFormatMessage = 'This value does not represent a valid week in the ISO 8601 format.',
        string $invalidWeekNumberMessage = 'This value is not a valid week.',
        string $tooLowMessage = 'This value should not be before week "{{ min }}".',
        string $tooHighMessage = 'This value should not be after week "{{ max }}".',
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function when(
        \Symfony\Component\ExpressionLanguage\Expression|array|string $expression,
        \Symfony\Component\Validator\Constraint|array|null $constraints = null,
        ?array $values = null,
        ?array $groups = null,
         $payload = null,
        array $options = [],
    ): ChainedInterface;

    public function wordCount(
        ?int $min = null,
        ?int $max = null,
        ?string $locale = null,
        string $minMessage = 'This value is too short. It should contain at least one word.|This value is too short. It should contain at least {{ min }} words.',
        string $maxMessage = 'This value is too long. It should contain one word.|This value is too long. It should contain {{ max }} words or less.',
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

    public function yaml(
        string $message = 'This value is not valid YAML.',
        int $flags = 0,
        ?array $groups = null,
        mixed $payload = null,
    ): ChainedInterface;

}
