<?php

namespace App\EventListener;

use App\Exception\ApiAccessDeniedHttpException;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class JWTAuthenticationSuccessListener
{
    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        $user = $event->getUser();

        if (!$user || !$user->isApiKeyActive() || !in_array('ROLE_API', $user->getRoles(), true)) {
            throw new ApiAccessDeniedHttpException();
        }
    }
}

