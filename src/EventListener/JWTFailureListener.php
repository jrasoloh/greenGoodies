<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTExpiredEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTInvalidEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTNotFoundEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

class JWTFailureListener
{
    public function onAuthenticationFailure(AuthenticationFailureEvent $event): void
    {
        $exception = $event->getException();
        $message = $exception->getMessage();
        $previousMessage = $exception->getPrevious()?->getMessage();

        if ($this->isApiKeyNotActivatedMessage($message) || $this->isApiKeyNotActivatedMessage($previousMessage)) {
            $event->setResponse(new JsonResponse([
                'code' => 403,
                'message' => 'Clé API non activée'
            ], 403));

            return;
        }

        // Keep Lexik's native invalid credentials response (401).
        if ($this->isInvalidCredentialsMessage($message) || $this->isInvalidCredentialsMessage($previousMessage)) {
            return;
        }

        $event->setResponse(new JsonResponse([
            'code' => 403,
            'message' => "Vous n'avez pas les autorisations requises"
        ], 403));
    }

    public function onJWTExpired(JWTExpiredEvent $event): void
    {
        $event->setResponse(new JsonResponse([
            'code' => 401,
            'message' => 'Token expiré'
        ], 401));
    }

    public function onJWTInvalid(JWTInvalidEvent $event): void
    {
        $event->setResponse(new JsonResponse([
            'code' => 401,
            'message' => 'Token invalide'
        ], 401));
    }

    public function onJWTNotFound(JWTNotFoundEvent $event): void
    {
        $event->setResponse(new JsonResponse([
            'code' => 401,
            'message' => 'Token manquant'
        ], 401));
    }

    private function isApiKeyNotActivatedMessage(?string $message): bool
    {
        if (!$message) {
            return false;
        }

        return str_contains($message, "Votre clé API n'est pas activée");
    }

    private function isInvalidCredentialsMessage(?string $message): bool
    {
        if (!$message) {
            return false;
        }

        return str_contains($message, 'Invalid credentials.');
    }
}
