<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Repository\UserRepository;

class UniqueLoginValidator extends ConstraintValidator
{
    public function __construct(private UserRepository $userRepository) {}

    public function validate($value, Constraint $constraint): void
    {
        if ($value && $this->userRepository->findOneBy(['login' => $value])) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
