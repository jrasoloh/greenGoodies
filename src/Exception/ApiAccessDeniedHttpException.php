<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ApiAccessDeniedHttpException extends AccessDeniedHttpException
{
    const API_ACCESS_DENIED_MESSAGE = 'Clé API non activée';
    public function __construct(\Throwable $previous = null)
    {
        parent::__construct(self::API_ACCESS_DENIED_MESSAGE, $previous);
    }
}
