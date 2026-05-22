<?php

namespace App\Manager;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Random\RandomException;

class AccountManager
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Toggles the API access state and generates a key if needed
     * @throws RandomException
     */
    public function toggleApiKey(User $user): void
    {
        $newState = !$user->isApiKeyActive();
        $user->setIsApiKeyActive($newState);

        if ($newState && !$user->getApiKey()) {
            $user->setApiKey('gg_lk_' . bin2hex(random_bytes(16)));
        }

        $this->entityManager->flush();
    }

    /**
     * Permanently deletes the user account
     */
    public function deleteAccount(User $user): void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}
