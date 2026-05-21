<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ApiUserChecker implements UserCheckerInterface
{
    // 🎯 S'exécute juste avant la vérification du mot de passe
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        // 🚨 Si l'accès API n'est pas activé, on jette une exception de sécurité
        if (!$user->isApiKeyActive()) {
            throw new CustomUserMessageAuthenticationException(
                "Votre accès API n'est pas activé. Veuillez l'activer depuis votre profil GreenGoodies."
            );
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        // Rien à faire après l'authentification dans ce cas
    }
}
