<?php

declare(strict_types=1);

namespace Application\Exception;

interface HttpCodeResolverInterface
{

    public function addExceptionStatusCode(string $exceptionFCQN, int $statusCode = 500): void;

    public function resolveCode(\Throwable $exception): int;

}
