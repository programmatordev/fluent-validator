# Fluent Validator

[Symfony Validator](https://symfony.com/doc/current/validation.html) wrapper to validate raw values in a fluent style.

## Features

- Validate any value in a fluent style;
- Constraints autocompletion;
- 3 validation methods: [`validate`](#validate), [`assert`](#assert) and [`isValid`](#isvalid);
- Custom constraints;
- Translations.

## Usage

Simple usage should look like this:

```php
use ProgrammatorDev\FluentValidator\Validator;

// let's assume we want to validate the age
$errors = Validator::notBlank()
    ->greaterThanOrEqual(18)
    ->lessThan(60)
    ->validate($age);

if ($errors->count() > 0) {
    // handle errors
}
```

Constraints autocompletion is available (i.e., constraints will be suggested as you type them) but, as a rule of thumb,
the chained method name should be the same as the original Symfony constraint class name but with a lowercased first letter.

- `NotBlank` => `notBlank`
- `All` => `all`
- `PasswordStrenght` => `passwordStrength`
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
and keep the fluent validation style:

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

To create custom constraints, follow the instructions on the [Symfony Validator documentation](https://symfony.com/doc/current/validation/custom_constraint.html).

Using the same example found in the documentation, creating a `ContainsAlphanumeric` constraint would require the following steps:

### 1. Create a Constraint Class

Let's assume your custom constraints will be in the `src/Constraint` directory.

All custom constraints should extend the `Constraint` class.

```php
// src/Constraint/ContainsAlphanumeric.php
namespace App\Constraint;

use Symfony\Component\Validator\Constraint;

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
}
```

### 2. Create the Validator Class

The validator class must be the name of the constraint class followed by `Validator`.
In this case, since the custom constraint class was named `ContainsAlphanumeric`, 
our validator should be named `ContainsAlphanumericValidator` and extend the `ConstraintValidator` class.

```php
// src/Constraint/ContainsAlphanumericValidator.php
namespace App\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ContainsAlphanumericValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ContainsAlphanumeric) {
            throw new UnexpectedTypeException($constraint, ContainsAlphanumeric::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) to take care of that
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, 'string');

            // separate multiple types using pipes
            // throw new UnexpectedValueException($value, 'string|int');
        }

        // access your configuration options like this:
        if ('strict' === $constraint->mode) {
            // ...
        }

        if (preg_match('/^[a-zA-Z0-9]+$/', $value, $matches)) {
            return;
        }

        // the argument must be a string or an object implementing __toString()
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', $value)
            ->addViolation();
    }
}
```

### 3. Add Namespace

Finally, add the namespace where the custom constraints will be located in your project.

```php
use ProgrammatorDev\FluentValidator\Validator;

Validator::addNamespace('App\Constraint');

Validator::notBlank()->containsAlphanumeric()->isValid('!'); // false
Validator::notBlank()->containsAlphanumeric()->isValud('v4l1d'); // true
```

You can have multiple constraints in the same namespace or have multiple namespaces.

Unfortunately, custom constraints will not be suggested in the autocompletion.

## Translations

You can set a global translator to handle all error messages translations.

This library already comes with one that will translate all error messages based on the translations provided by Symfony Validator.
Check all [available translations](https://github.com/symfony/symfony/tree/7.2/src/Symfony/Component/Validator/Resources/translations).

```php
use ProgrammatorDev\FluentValidator\Translator\Translator;

// set translator to Portuguese (Portugal) locale
Validator::setTranslator(new Translator('pt'));

// now all error messages will be in Portuguese
Validator::notBlank()->validate(''); // Este valor não deveria ser vazio.
```

## Contributing

Any form of contribution to improve this library (including requests) will be welcome and appreciated.
Make sure to open a pull request or issue.

## License

This project is licensed under the MIT license.
Please see the [LICENSE](LICENSE) file distributed with this source code for further information regarding copyright and licensing.