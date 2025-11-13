<?php

namespace App\Validator\Constraints;

use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

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
