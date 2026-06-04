<?php

namespace App\EventListener;

use App\Exception\ApiAccessDeniedHttpException;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTFailureException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class JWTAuthenticationSuccessListener
{
    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        $user = $event->getUser();

        if (!in_array('ROLE_API', $user->getRoles(), true)) {
           /* $event->getResponse()->setStatusCode(Response::HTTP_FORBIDDEN);
            $event->setData([
                'code' => Response::HTTP_FORBIDDEN,
                'message' => 'Clé API non activée'
            ]);*/

            throw new ApiAccessDeniedHttpException();
        }
    }
}

