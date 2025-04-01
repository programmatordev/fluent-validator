<?php

namespace ProgrammatorDev\FluentValidator\Test;

use ProgrammatorDev\FluentValidator\Exception\NoSuchConstraintException;
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

        $this->validator = Validator::notBlank()
            ->greaterThanOrEqual(18)
            ->lessThan(25);
    }

    public function testConstraintThatIsInvalid(): void
    {
        // NotBlankValidator class exists in "Symfony\Component\Validator\Constraints" namespace
        // but throws error because it is not an instance of Constraint
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

    public function testAssertFail(): void
    {
        $this->expectException(ValidationFailedException::class);
        $this->validator->assert(16);
    }

    public function testAssertSuccess(): void
    {
        $this->validator->assert(18);
        $this->assertTrue(true);
    }

    public function testIsValid(): void
    {
        $this->assertFalse($this->validator->isValid(16));
        $this->assertTrue($this->validator->isValid(18));
    }

    public function testToArray(): void
    {
        $constraints = $this->validator->toArray();

        $this->assertInstanceOf(NotBlank::class, $constraints[0]);
        $this->assertInstanceOf(GreaterThanOrEqual::class, $constraints[1]);
        $this->assertInstanceOf(LessThan::class, $constraints[2]);
    }

    public function testCustomConstraint(): void
    {
        Validator::addNamespace('ProgrammatorDev\FluentValidator\Test\Constraint');

        $this->assertFalse(Validator::containsAlphanumeric()->isValid('!'));
        $this->assertTrue(Validator::containsAlphanumeric()->isValid('v4l1d'));
    }

    public function testSetTranslator(): void
    {
        // by default, error is in English
        $violations = $this->validator->validate('');
        $this->assertEquals('This value should not be blank.', $violations->get(0)->getMessage());

        // set translator and then try again
        Validator::setTranslator(new Translator('pt'));
        // now error is in Portuguese
        $violations = $this->validator->validate('');
        $this->assertEquals('Este valor não deveria ser vazio.', $violations->get(0)->getMessage());
    }
}