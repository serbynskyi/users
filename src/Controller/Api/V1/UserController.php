<?php
namespace App\Controller\Api\V1;

use App\Dto\UserCreateDto;
use App\Dto\UserUpdateDto;
use App\Security\Attribute\UserExists;
use App\Security\Attribute\UserIsSelfOrAdmin;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/v1/api/users')]
class UserController extends AbstractController
{
    public function __construct(
        private UserService $userService
    ) {}

    #[Route('/{id}', name: 'get', methods: ['GET'])]
    public function getUserById(#[UserExists, UserIsSelfOrAdmin] int $id): JsonResponse
    {
        return $this->json($this->userService->getUser($id));
    }

    #[Route('', name: 'create', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(#[MapRequestPayload(validationGroups: ['Default'])] UserCreateDto $dto): JsonResponse
    {
        return $this->json(
            [
                'message' => 'User created',
                'user' => $this->userService->createUser($dto)
            ],
            201
        );
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(
        #[MapRequestPayload(validationGroups: ['Default'])] UserUpdateDto $dto,
        #[UserExists, UserIsSelfOrAdmin] int $id
    ): JsonResponse
    {
        $user = $this->userService->updateUser($id, $dto);

        return $this->json(
            [
                'message' => 'User updated',
                'user' => $user
            ]
        );
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(#[UserExists] int $id): JsonResponse
    {
        $this->userService->deleteUser($id);

        return $this->json(['message' => 'User deleted']);
    }
}
