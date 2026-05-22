<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

class JWTFailureListener
{
    public function onAuthenticationFailure(AuthenticationFailureEvent $event): void
    {
        $exception = $event->getException();

        // 🎯 Si l'erreur provient du Checker, on force la structure et le statut 403 demandés
        if ($exception->getMessage() === 'Accès API non activé' ||
            ($exception->getPrevious() && $exception->getPrevious()->getMessage() === 'Accès API non activé')) {

            $response = new JsonResponse([
                'code' => 403,
                'message' => 'Accès API non activé'
            ], 403);

            $event->setResponse($response);
        }
    }
}
