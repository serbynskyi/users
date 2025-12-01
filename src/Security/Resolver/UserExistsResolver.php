<?php

namespace App\Security\Resolver;

use App\Security\Attribute\UserExists;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserExistsResolver implements ValueResolverInterface
{
    public function __construct(private UserRepository $userRepository) {}

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $attributes = $argument->getAttributes(UserExists::class);
        if (empty($attributes)) {
            return [];
        }

        $id = $request->attributes->get($argument->getName());
        $user = $this->userRepository->find($id);

        if (!$user) {
            throw new NotFoundHttpException("User with id $id not found");
        }

        yield $id;
    }
}
