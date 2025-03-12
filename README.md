# Fluent Validator

A Symfony Validator wrapper that enables fluent-style validation for raw values, 
offering an easy-to-use and intuitive API to validate user input or other data in a concise and readable manner.

## Features

- 🌊 **Fluent-style validation:** Chain validation methods for better readability and flow.
- 🤘 **Constraints autocompletion:** Enables IDE autocompletion for available constraints.
- 🔥 **Three validation methods:** Use `validate`, `assert`, or `isValid` based on the context (i.e., collect errors or throw exceptions).
- ⚙️ **Custom constraints:** Easily integrate custom validation logic with Symfony's Validator system.
- 💬 **Translations support:** Translate validation error messages into multiple languages.

## Requirements

- PHP 8.2 or higher.

## Installation

Install via [Composer](https://getcomposer.org/):

```bash
composer require programmatordev/fluent-validator
```

## Usage

Simple usage should look like this:

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

Constraints autocompletion is available in IDEs like PhpStorm (i.e., constraints will be suggested as you type them). 
As a rule of thumb, the chained method name should be the same as the original Symfony constraint class name, 
but with a lowercased first letter.

- `NotBlank` => `notBlank`
- `All` => `all`
- `PasswordStrength` => `passwordStrength`
- ...and so on.

For more information about all available constraints, check the [Constraints](#constraints) section below.

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

This method returns a `ConstraintViolationList` object, which acts like an array of errors. 
Each error in the collection is a `ConstraintViolation` object, which holds the error message on its `getMessage()` method.

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

This method will throw a `ValidationFailedException` when a validation has failed.

```php
use ProgrammatorDev\FluentValidator\Exception\ValidationFailedException;
use ProgrammatorDev\FluentValidator\Validator;
use Symfony\Component\Validator\Constraints\PasswordStrength;

try {
    Validator::notBlank()->assert($name);
    Validator::notBlank()->email()->assert($email);
    Validator::notBlank()->passwordStrength(minScore: PasswordStrength::STRENGTH_VERY_STRONG)->assert($password);
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

Useful for conditions, this method will return a `bool`.

```php
use ProgrammatorDev\FluentValidator\Validator;

if (!Validator::email()->isValid($email)) {
    // do something when the email is not valid
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
and keeps the fluent validation style:

```php
use ProgrammatorDev\FluentValidator\Validator;

$value = [10, 3, 110];

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

Check the [Custom Constraints](#custom-constraints) section for more information.

### `setTranslator`

```php
use Symfony\Contracts\Translation\TranslatorInterface;

setTranslator(?TranslatorInterface $translator): void
```

Used to add a translator for the translation of all constraint error messages.

Check the [Translations](#translations) section for more information.

## Custom Constraints

To create custom constraints, follow the instructions outlined in the [Symfony Validator documentation](https://symfony.com/doc/current/validation/custom_constraint.html).

Using the same example found in the documentation, creating a `ContainsAlphanumeric` constraint would require the following steps:

### 1. Create a Constraint Class

This class defines the error message and configurable options.

```php
// src/Constraint/ContainsAlphanumeric.php
namespace App\Constraint;

use Symfony\Component\Validator\Constraint;

class ContainsAlphanumeric extends Constraint
{
    // ...
}
```

### 2. Create the Validator Class

The validator checks if the value complies with the constraint rules.

```php
// src/Constraint/ContainsAlphanumericValidator.php
namespace App\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsAlphanumericValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        // ...
    }
}
```

### 3. Add Namespace

Finally, register the namespace where the custom constraints will be located in your project.

```php
use ProgrammatorDev\FluentValidator\Validator;

Validator::addNamespace('App\Constraint');

Validator::notBlank()->containsAlphanumeric()->isValid('!'); // false
Validator::notBlank()->containsAlphanumeric()->isValid('v4l1d'); // true
```

You can have multiple constraints in the same namespace or have multiple namespaces.

Unfortunately, custom constraints will not be suggested in the autocompletion.

## Translations

You can set a global translator to handle all error messages translations. 
The library comes with a default translator that supports Symfony Validator translations.

```php
use ProgrammatorDev\FluentValidator\Translator\Translator;

// set translator to Portuguese (Portugal) locale
Validator::setTranslator(new Translator('pt'));

// now all error messages will be in Portuguese
Validator::notBlank()->validate(''); // Este valor não deveria ser vazio.
```

To add your own translations, you can integrate a custom translator or extend the default one.

## Contributing

Any form of contribution to improve this library (including requests) will be welcome and appreciated.
Make sure to open a pull request or issue.

## License

This project is licensed under the MIT license.
Please see the [LICENSE](LICENSE) file distributed with this source code for further information regarding copyright and licensing.