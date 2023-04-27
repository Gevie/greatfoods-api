<?php

declare(strict_types=1);

namespace App\Api\V1\Controller;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends ApiController
{
    public function index(UserPasswordHasherInterface $passwordHasher)
    {
        $user = new User();
        $plaintextPassword = 'test';
        
        $hashedPassword = $passwordHasher->hashPassword($user, $plaintextPassword);
        $user->setPassword($hashedPassword);
    }
}
