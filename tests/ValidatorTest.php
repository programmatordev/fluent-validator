<?php

namespace ProgrammatorDev\FluentValidator\Test;

use ProgrammatorDev\FluentValidator\Exception\NoSuchConstraintException;
use ProgrammatorDev\FluentValidator\Exception\NoSuchTranslationException;
use ProgrammatorDev\FluentValidator\Exception\ValidationFailedException;
use ProgrammatorDev\FluentValidator\Translator\Translator;
use ProgrammatorDev\FluentValidator\Validator;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolationList;

class ValidatorTest extends AbstractTestCase
{
    private Validator $validator;

    protected function setUp(): void
    {
        parent::setUp();

        Validator::reset();

        $this->validator = Validator::notBlank()
            ->greaterThanOrEqual(18)
            ->lessThan(25);
    }

    protected function tearDown(): void
    {
        Validator::reset();

        parent::tearDown();
    }

    public function testConstraintThatIsInvalid(): void
    {
        // NotBlankValidator class exists in "Symfony\Component\Validator\Constraints" namespace
        // but throws an error because it is not an instance of Constraint
        $this->expectException(NoSuchConstraintException::class);
        Validator::notBlankValidator();
    }

    public function testConstraintThatDoesNotExist(): void
    {
        $this->expectException(NoSuchConstraintException::class);
        Validator::noSuchConstraint();
    }

    public function testValidate(): void
    {
        $violations = $this->validator->validate(16);
        $this->assertInstanceOf(ConstraintViolationList::class, $violations);
        $this->assertCount(1, $violations);

        $violations = $this->validator->validate(18);
        $this->assertInstanceOf(ConstraintViolationList::class, $violations);
        $this->assertCount(0, $violations);
    }

    public function testValidateWithoutConstraints(): void
    {
        $violations = (new Validator())->validate('anything');

        $this->assertInstanceOf(ConstraintViolationList::class, $violations);
        $this->assertCount(0, $violations);
    }

    public function testValidateWithGroups(): void
    {
        $validator = Validator::notBlank(groups: ['Default'])
            ->email(groups: ['registration']);

        $violations = $validator->validate('invalid-email', groups: ['Default']);
        $this->assertCount(0, $violations);

        $violations = $validator->validate('invalid-email', groups: ['registration']);
        $this->assertCount(1, $violations);
    }

    public function testAssertFail(): void
    {
        $this->expectException(ValidationFailedException::class);
        $this->validator->assert(16);
    }

    public function testAssertFailWithName(): void
    {
        $this->expectException(ValidationFailedException::class);
        $this->expectExceptionMessage('age: This value should be greater than or equal to 18.');

        $this->validator->assert(16, 'age');
    }

    public function testAssertSuccess(): void
    {
        $this->validator->assert(18);
        $this->assertTrue(true);
    }

    public function testAssertWithGroups(): void
    {
        $validator = Validator::notBlank(groups: ['Default'])
            ->email(groups: ['registration']);

        $validator->assert('invalid-email', groups: ['Default']);
        $this->assertTrue(true);
    }

    public function testAssertWithGroupsFail(): void
    {
        $validator = Validator::notBlank(groups: ['Default'])
            ->email(groups: ['registration']);

        $this->expectException(ValidationFailedException::class);
        $this->expectExceptionMessage('This value is not a valid email address.');

        $validator->assert('invalid-email', groups: ['registration']);
    }

    public function testIsValid(): void
    {
        $this->assertFalse($this->validator->isValid(16));
        $this->assertTrue($this->validator->isValid(18));
    }

    public function testIsValidWithoutConstraints(): void
    {
        $this->assertTrue((new Validator())->isValid('anything'));
    }

    public function testIsValidWithGroups(): void
    {
        $validator = Validator::notBlank(groups: ['Default'])
            ->email(groups: ['registration']);

        $this->assertTrue($validator->isValid('invalid-email', groups: ['Default']));
        $this->assertFalse($validator->isValid('invalid-email', groups: ['registration']));
    }

    public function testToArray(): void
    {
        $constraints = $this->validator->toArray();

        $this->assertInstanceOf(NotBlank::class, $constraints[0]);
        $this->assertInstanceOf(GreaterThanOrEqual::class, $constraints[1]);
        $this->assertInstanceOf(LessThan::class, $constraints[2]);
    }

    public function testToArrayWithoutConstraints(): void
    {
        $this->assertSame([], (new Validator())->toArray());
    }

    public function testCustomConstraint(): void
    {
        Validator::addNamespace('ProgrammatorDev\FluentValidator\Test\Fixtures\Constraint');

        $this->assertFalse(Validator::containsAlphanumeric()->isValid('!'));
        $this->assertTrue(Validator::containsAlphanumeric()->isValid('v4l1d'));
    }

    public function testResetClearsCustomConstraintNamespaces(): void
    {
        Validator::addNamespace('ProgrammatorDev\FluentValidator\Test\Fixtures\Constraint');
        $this->assertTrue(Validator::containsAlphanumeric()->isValid('v4l1d'));

        Validator::reset();

        $this->expectException(NoSuchConstraintException::class);
        Validator::containsAlphanumeric();
    }

    public function testSetTranslator(): void
    {
        // by default, the error is in English
        $violations = $this->validator->validate('');
        $this->assertEquals('This value should not be blank.', $violations->get(0)->getMessage());

        // set translator and then try again
        Validator::setTranslator(new Translator('pt'));
        // now the error is in Portuguese
        $violations = $this->validator->validate('');
        $this->assertEquals('Este valor não deveria ser vazio.', $violations->get(0)->getMessage());
    }

    public function testSetTranslatorThatDoesNotExist(): void
    {
        $this->expectException(NoSuchTranslationException::class);
        $this->expectExceptionMessage('Translation for locale "zz" was not found.');

        new Translator('zz');
    }

    public function testResetClearsTranslator(): void
    {
        Validator::setTranslator(new Translator('pt'));
        $violations = $this->validator->validate('');
        $this->assertEquals('Este valor não deveria ser vazio.', $violations->get(0)->getMessage());

        Validator::reset();

        $violations = $this->validator->validate('');
        $this->assertEquals('This value should not be blank.', $violations->get(0)->getMessage());
    }
}
