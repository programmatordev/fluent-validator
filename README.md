# Fluent Validator

A [Symfony Validator](https://symfony.com/doc/current/validation.html) wrapper that enables fluent-style validation for raw values, 
offering an easy-to-use and intuitive API to validate user input or other data in a concise and readable manner.

## Features

- 🌊 **Fluent-style validation:** Chain validation methods for better readability and flow.
- 🤘 **Constraints autocompletion:** Enables IDE autocompletion for available constraints.
- 🔥 **Three validation methods:** Use `validate`, `assert`, or `isValid` based on the context (i.e., collect errors or throw exceptions).
- ⚙️ **Custom constraints:** Easily integrate custom validation logic with Symfony's Validator system.
- 💬 **Translations support:** Translate validation error messages into multiple languages.

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)
- [Constraints](#constraints)
- [Methods](#methods)
  - [validate](#validate)
  - [assert](#assert)
  - [isValid](#isvalid)
  - [getConstraints](#getconstraints)
  - [addNamespace](#addnamespace)
  - [setTranslator](#settranslator)
- [Custom Constraints](#custom-constraints)
- [Translations](#translations)

## Requirements

- PHP 8.2 or higher.

## Installation

Install via [Composer](https://getcomposer.org/):

```bash
composer require programmatordev/fluent-validator
```

## Usage

Simple usage example:

```php
use ProgrammatorDev\FluentValidator\Validator;

// example: validate the user's age to ensure it's between 18 and 60
$errors = Validator::notBlank()
    ->greaterThanOrEqual(18)
    ->lessThan(60)
    ->validate($age);

if ($errors->count() > 0) {
    // handle errors
}
```

Constraints autocompletion is available in IDEs like PhpStorm. 
The method names match Symfony constraints but with a lowercase first letter:

- `NotBlank` => `notBlank`
- `All` => `all`
- `PasswordStrength` => `passwordStrength`
- ...and so on.

For all available constraints, check the [Constraints](#constraints) section.

For all available methods, check the [Methods](#methods) section.

There is also a section for [Custom Constraints](#custom-constraints) and [Translations](#translations).

## Constraints

All available constraints can be found on the [Symfony Validator documentation](https://symfony.com/doc/current/validation.html#constraints).

For custom constraints, check the [Custom Constraints](#custom-constraints) section.

## Methods

### `validate`

```php
use Symfony\Component\Validator\Constraints\GroupSequence;

validate(mixed $value, ?string $name = null, string|GroupSequence|array|null $groups = null): ConstraintViolationListInterface
```

Returns a `ConstraintViolationList` object, acting as an array of errors.

```php
use ProgrammatorDev\FluentValidator\Validator;

$errors = Validator::email()->validate('test@email.com');

if ($errors->count() > 0) {
    foreach ($errors as $error) {
        $message = $error->getMessage();
        // ...
    }
}
```

### `assert`

```php
use Symfony\Component\Validator\Constraints\GroupSequence;

assert(mixed $value, ?string $name = null, string|GroupSequence|array|null $groups = null): void
```

Throws a `ValidationFailedException` when validation fails.

```php
use ProgrammatorDev\FluentValidator\Exception\ValidationFailedException;
use ProgrammatorDev\FluentValidator\Validator;

try {
    Validator::notBlank()->assert($name);
    Validator::notBlank()->email()->assert($email);
}
catch (ValidationFailedException $exception) {
    // exception message will always be the first error thrown
    $message = $exception->getMessage();
    // value that failed validation
    $value = $exception->getValue();
    // get access to all errors
    // returns a ConstraintViolationList object like in the validate method
    $errors = $exception->getViolations();
    
    // ...
}
```

### `isValid`

```php
use Symfony\Component\Validator\Constraints\GroupSequence;

isValid(mixed $value, string|GroupSequence|array|null $groups = null): bool
```

Returns a `bool` indicating if the value is valid.

```php
use ProgrammatorDev\FluentValidator\Validator;

if (!Validator::email()->isValid($email)) {
    // handle invalid email
}
```

### `getConstraints`

```php
use Symfony\Component\Validator\Constraint;

/** @return Constraint[] */
getConstraints(): array
```

Returns an array with all added constraints.

```php
use ProgrammatorDev\FluentValidator\Validator;

$constraints = Validator::notBlank()->email()->getConstraints();
```

It is useful for `Composite` constraints (i.e., a constraint that is composed of other constraints) 
and keeps the fluent-style validation:

```php
use ProgrammatorDev\FluentValidator\Validator;

// validate that array should have at least one value
// and each value should be between 0 and 100
$errors = Validator::count(min: 1)
    ->all(Validator::range(min: 0, max: 100)->getConstraints())
    ->validate($value);
```

### `addNamespace`

```php
addNamespace(string $namespace): void
```

Used to add namespaces for custom constraints. 

Check the [Custom Constraints](#custom-constraints) section.

### `setTranslator`

```php
use Symfony\Contracts\Translation\TranslatorInterface;

setTranslator(?TranslatorInterface $translator): void
```

Used to add a translator for validation error message translations.

Check the [Translations](#translations) section.

## Custom Constraints

If you need a custom constraint, follow the Symfony Validator documentation: [Creating Custom Constraints](https://symfony.com/doc/current/validation/custom_constraint.html).

### Example: Creating a `ContainsAlphanumeric` Constraint

#### 1. Create a Constraint Class

This class defines the error message and configurable options.

```php
namespace App\Constraint;

use Symfony\Component\Validator\Constraint;

class ContainsAlphanumeric extends Constraint
{
    // set configurable options
}
```

#### 2. Create the Validator Class

The validator checks if the value complies with the constraint rules.

```php
namespace App\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsAlphanumericValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        // custom validation logic
    }
}
```

#### 3. Register the Constraint Namespace

Register the namespace where the custom constraints will be located in your project.

```php
use ProgrammatorDev\FluentValidator\Validator;

Validator::addNamespace('App\Constraint');

Validator::notBlank()->containsAlphanumeric()->isValid('!'); // false
Validator::notBlank()->containsAlphanumeric()->isValid('v4l1d'); // true
```

You can have multiple constraints in the same namespace or have multiple namespaces.

> [!NOTE]
> Custom constraints will not be suggested in IDE autocompletion.

## Translations

Set a global translator to handle error message translations.

```php
use ProgrammatorDev\FluentValidator\Translator\Translator;

// set translator to Portuguese (Portugal) locale
Validator::setTranslator(new Translator('pt'));

// now all error messages will be in Portuguese
Validator::notBlank()->validate('');
```

To add your own translations, you can integrate a custom translator.

## Contributing

Any form of contribution to improve this library (including requests) will be welcome and appreciated.
Make sure to open a pull request or issue.

## License

This project is licensed under the MIT license.
Please see the [LICENSE](LICENSE) file distributed with this source code for further information regarding copyright and licensing.