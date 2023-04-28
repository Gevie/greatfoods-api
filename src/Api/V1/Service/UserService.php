<?php

declare(strict_types=1);

namespace App\Api\V1\Service;

use App\Api\V1\Dto\UserDto;
use App\Api\V1\Repository\UserRepository;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class UserService
 *
 * Provides a service for creating and managing user entities.
 *
 * @package App\Api\V1\Service
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
class UserService
{
    /**
     * UserService constructor.
     *
     * @param EntityManagerInterface $entityManager The entity manager
     * @param UserRepository $userRepository The user repository
     */
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected UserPasswordHasherInterface $passwordHasher,
        protected UserRepository $userRepository
    ) {
        // ...
    }

    /**
     * Creates a new user entity with the given name, description and order.
     *
     * @param UserDto $userDto The user Data Transfer Object
     * @param bool $save Whether to save the user entity, default is true
     *
     * @return User The newly created user entity
     */
    public function create(UserDto $userDto, bool $save = true): User
    {
        $user = new User();
        $user->setEmail($userDto->email);
        $user->setPassword($this->hashPassword($user, $userDto->password));
        // Todo: Roles

        if ($save) {
            $this->userRepository->save($user, true);
        }

        return $user;
    }

    /**
     * Soft deletes a user entity.
     *
     * @param User $user The user entity
     *
     * @return void
     */
    public function delete(User $user): void
    {
        $this->userRepository->remove($user, true);
    }

    /**
     * Hashes a plain text password for the given user.
     *
     * @param User $user The user
     * @param string $password The plaintext password
     * 
     * @return string The hashed password
     */
    private function hashPassword(User $user, string $password): string
    {
        return $this->passwordHasher->hashPassword($user, $password);
    }

    /**
     * Updates a user entity with the given name, description and order.
     *
     * @param User $user The user entity to update
     * @param UserDto $userDto The user Data Transfer Object
     *
     * @return user The newly updated user entity
     */
    public function update(User $user, UserDto $userDto): User
    {
        $user->setEmail($userDto->email);
        $user->setPassword($this->hashPassword($user, $userDto->password));
        // Todo: Roles

        $this->userRepository->save($user, true);

        return $user;
    }
}
