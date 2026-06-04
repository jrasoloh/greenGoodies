<?php

namespace App\Manager;

use App\Entity\User;
use App\Repository\UserRepository;
use Random\RandomException;

class AccountManager
{
    public function __construct(private readonly UserRepository $userRepository)
    {
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

        $this->userRepository->save();
    }

    /**
     * Permanently deletes the user account
     */
    public function deleteAccount(User $user): void
    {
        $this->userRepository->remove($user);
    }
}
