<?php

namespace App\Controller\Api\V1;

use App\Entity\User;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/v1/api/users', name: 'api_users_')]
class UserApiController extends AbstractController
{
    public function __construct(
        private readonly UserService   $userService,
        private readonly EntityManagerInterface $em
    ) {}

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $authHeader = $request->headers->get('Authorization');
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return $this->json(['error' => 'Missing API token'], 401);
        }

        $token = substr($authHeader, 7);
        $user = $this->em->getRepository(User::class)->findOneBy(['apiToken' => $token]);

        if (!$user) {
            return $this->json(['error' => 'Invalid API token'], 401);
        }

        if (!in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return $this->json(['error' => 'Access denied'], 403);
        }

        $data = json_decode($request->getContent(), true);
        if (!isset($data['login'], $data['password'], $data['phone'])) {
            return $this->json(['error' => 'login, password and phone required'], 400);
        }

        $roles = $data['roles'] ?? ['ROLE_USER'];
        $newUser = $this->userService->createUser($data['login'], $data['password'], $data['phone'], $roles);

        return $this->json([
            'id' => $newUser->getId(),
            'login' => $newUser->getLogin(),
            'roles' => $newUser->getRoles(),
            'apiToken' => $newUser->getApiToken(),
        ], 201);
    }
}
