<?php

namespace App\Normalizer;

use App\Entity\User;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserNormalizer implements NormalizerInterface
{
    public function supportsNormalization($object, string $format = null, array $context = []): bool
    {
        return $object instanceof User;
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        return [
            'id' => $object->getId(),
            'login' => $object->getLogin(),
            'phone' => $object->getPhone(),
            'roles' => $object->getRoles(),
        ];
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            'App\Entity\User' => true,
        ];
    }
}
