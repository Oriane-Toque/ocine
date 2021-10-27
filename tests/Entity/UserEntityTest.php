<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

//test parcours navigateur
class UserEntityTest extends KernelTestCase
{
    // VARIABLES TEST EMAIL
    private const VALID_EMAIL = 'oriane.toque@gmail.com';

    // contrainte email valide
    private const INVALID_EMAIL_MESSAGE = 'Cette adresse "oriane.toque@gmail" n\'est pas une adresse mail valide';
    private const INVALID_EMAIL = 'oriane.toque@gmail';

    // contrainte email fournis
    private const NOT_BLANK_EMAIL_MESSAGE = 'Veuillez saisir une valeur';


    // VARIABLES TEST PASSWORD
    private const VALID_PASSWORD = 'Moon.maxie22?';
    private const NOT_BLANK_PASSWORD_MESSAGE = 'Veuillez saisir une valeur';
    private const REGEX_INVALID_PASSWORD_MESSAGE = 'Mot de passe invalide';

    // VARIABLES TEST ROLE
    private const VALID_ROLE = ['ROLE_USER'];
    private const INVALID_ROLE = ['ROLE_USER', 'ROLE_ADMIN'];
    private const INVALID_BLANK_ROLE = [];
    private const INVALID_ROLE_MESSAGE = 'Cette collection doit contenir exactement 1 élément.';

    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->validator = $kernel->getContainer()->get('validator');
    }

    public function testUserEntityIsValid(): void
    {
        $user = new User;

        $user
            ->setEmail(self::VALID_EMAIL)
            ->setRoles(self::VALID_ROLE)
            ->setPassword(self::VALID_PASSWORD);

        $this->getValidationErrors($user, 0);
    }

    public function testUserEntityIsInvalidBecauseMoreRole(): void
    {
        $user = new User;

        $user
            ->setEmail(self::VALID_EMAIL)
            ->setRoles(self::INVALID_ROLE)
            ->setPassword(self::VALID_PASSWORD);

        $errors = $this->getValidationErrors($user, 1);

        $this->assertEquals(self::INVALID_ROLE_MESSAGE, $errors[0]->getMessage());
    }

    public function testUserEntityIsInvalidBecauseAnyRoleEntered(): void
    {
        $user = new User;

        $user
            ->setEmail(self::VALID_EMAIL)
            ->setRoles(self::INVALID_BLANK_ROLE)
            ->setPassword(self::VALID_PASSWORD);

        $errors = $this->getValidationErrors($user, 1);

        $this->assertEquals(self::INVALID_ROLE_MESSAGE, $errors[0]->getMessage());
    }

    public function testUserEntityIsInvalidBecauseNoEmail(): void
    {
        $user = new User();

        $user
            ->setPassword(self::VALID_PASSWORD)
            ->setRoles(self::VALID_ROLE);

        $errors = $this->getValidationErrors($user, 1);

        $this->assertEquals(self::NOT_BLANK_EMAIL_MESSAGE, $errors[0]->getMessage());
    }

    public function testUserEntityIsInvalidBecauseNoPassword(): void
    {
        $user = new User();

        $user
            ->setEmail(self::VALID_EMAIL)
            ->setRoles(self::VALID_ROLE);

        $errors = $this->getValidationErrors($user, 1);

        $this->assertEquals(self::NOT_BLANK_PASSWORD_MESSAGE, $errors[0]->getMessage());
    }

    public function testUserEntityIsInvalidBecauseInvalidEmail(): void
    {
        $user = new User();

        $user
            ->setEmail(self::INVALID_EMAIL)
            ->setPassword(self::VALID_PASSWORD)
            ->setRoles(self::VALID_ROLE);

        $errors = $this->getValidationErrors($user, 1);

        $this->assertEquals(self::INVALID_EMAIL_MESSAGE, $errors[0]->getMessage());
    }

    /**
     * @dataProvider provideInvalidPasswords
     */
    public function testUserEntityIsInvalidBecauseInvalidRegexPassword(string $invalidPassword): void
    {
        $user = new User();

        $user
            ->setEmail(self::VALID_EMAIL)
            ->setPassword($invalidPassword)
            ->setRoles(self::VALID_ROLE);

        $errors = $this->getValidationErrors($user, 1);

        $this->assertEquals(self::REGEX_INVALID_PASSWORD_MESSAGE, $errors[0]->getMessage());
    }

    public function provideInvalidPasswords(): array
    {
        return [
            'Sans caractères spéciaux'=>['salutjo22'], // pas caractères spéciaux
            'Sans chiffres'=>['silversta?'], // pas chiffres
            'Moins de 8 caractères'=>['yapre?'], // pas 8 caractères
            'Pas de lettres'=>['???222?'] // pas de caractères
        ];
    }

    private function getValidationErrors(User $user, int $numberOfExpectedErrors): ConstraintViolationList
    {
        $errors = $this->validator->validate($user);

        $this->assertCount($numberOfExpectedErrors, $errors);

        return $errors;
    }
}
