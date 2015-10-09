<?php

namespace Icse\MembersBundle\Validator\Constraints;

use Icse\MembersBundle\Security\Authentication\PasswordChecker;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UserPasswordValidator extends ConstraintValidator
{
    /** @var PasswordChecker */
    private $passwordChecker;
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage, PasswordChecker $passwordChecker)
    {
        $this->tokenStorage = $tokenStorage;
        $this->passwordChecker = $passwordChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($password, Constraint $constraint)
    {
        if (!$constraint instanceof UserPassword) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\UserPassword');
        }

        $user = $this->tokenStorage->getToken()->getUser();

        if (!$user instanceof UserInterface) {
            throw new ConstraintDefinitionException('The User object must implement the UserInterface interface.');
        }

        if (!$this->passwordChecker->queryPasswordCorrect($user, $password)) {
            $this->context->addViolation($constraint->message);
        }
    }
}
