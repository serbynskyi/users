<?php

namespace App\Validator;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueLoginIfChangedValidator extends ConstraintValidator
{
    public function __construct(
        private UserRepository $userRepository,
        private RequestStack $requestStack
    ) {}

    public function validate($dto, Constraint $constraint): void
    {
        if (!$dto || $dto->login === null) {
            return;
        }

        $request = $this->requestStack->getCurrentRequest();
        $user = $this->userRepository->find($request->get('id'));

        if ($user && $user->getLogin() === $dto->login) {
            return;
        }

        if ($this->userRepository->findOneBy(['login' => $dto->login])) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $dto->login)
                ->atPath('login')
                ->addViolation();
        }
    }
}
