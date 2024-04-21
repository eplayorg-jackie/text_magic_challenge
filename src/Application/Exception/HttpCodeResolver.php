<?php

declare(strict_types=1);

namespace Application\Exception;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class HttpCodeResolver implements HttpCodeResolverInterface
{

    private array $classes = [];

    public function addExceptionStatusCode(string $exceptionFCQN, int $statusCode = 500): void
    {
        $this->classes[$exceptionFCQN] = $statusCode;
    }

    public function resolveCode(\Throwable $exception): int
    {
        if ($exception instanceof HttpExceptionInterface) {
            return $exception->getStatusCode();
        }

        $class = \get_class($exception);

        return $this->classes[$class] ?? 500;
    }

}
