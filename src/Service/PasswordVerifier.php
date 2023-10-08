<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordVerifier
{
    private $passwordEncoder;

    public function __construct(UserPasswordHasherInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function verifyPassword(User $user, string $plainPassword): bool
    {
        return $this->passwordEncoder->isPasswordValid($user, $plainPassword);
    }
}
