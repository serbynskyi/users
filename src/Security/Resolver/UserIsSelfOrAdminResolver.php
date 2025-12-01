<?php

namespace App\Security\Resolver;

use App\Security\Attribute\UserIsSelfOrAdmin;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class UserIsSelfOrAdminResolver implements ValueResolverInterface
{
    public function __construct(private Security $security) {}

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $attributes = $argument->getAttributes(UserIsSelfOrAdmin::class);
        if (empty($attributes)) {
            return [];
        }

        $currentUser = $this->security->getUser();
        if (!$currentUser) {
            throw new AccessDeniedHttpException('Authentication required');
        }

        $id = $request->attributes->get($argument->getName());

        if ($id !== null) {
            $id = (int) $id;
        }

        if ($this->security->isGranted('ROLE_ADMIN') || $currentUser->getId() == $id) {
            yield $id;
        } else {
            throw new AccessDeniedHttpException('Access denied');
        }
    }
}
