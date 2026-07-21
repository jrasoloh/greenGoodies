<?php

namespace App\EventListener;

use App\Exception\ApiAccessDeniedHttpException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(event: KernelEvents::EXCEPTION, priority: 10)]
class ApiAccessExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ApiAccessDeniedHttpException) {
            $event->setResponse(new JsonResponse([
                'code' => $exception->getStatusCode(),
                'message' => $exception->getMessage(),
            ], $exception->getStatusCode()));
        }
    }
}
