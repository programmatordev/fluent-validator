# Fluent Validator

[Symfony Validator](https://symfony.com/doc/current/validation.html) wrapper to validate raw values in a fluent way.

## Features

- Validate any value in a fluent way;
- Constraints autocompletion;
- 3 validation methods with `validate`, `assert` and `isValid`;
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

All available constraints can be found at the [Symfony Validator official documentation](https://symfony.com/doc/current/validation.html#constraints).

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

if ($errors->count() => 0) {
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
to keep the fluent validation style:

```php
use ProgrammatorDev\FluentValidator\Validator;

// validate that all values from array are between 0 and 100
$value = [10, 3, 110];
$errors = Validator::all(Validator::range(min: 0, max: 100)->getConstraints())->validate($value);
```

## Custom Constraints

## Translations