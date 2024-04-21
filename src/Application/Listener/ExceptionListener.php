<?php

declare(strict_types=1);

namespace Application\Listener;

use Application\Exception\HttpCodeResolverInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

#[AsEventListener(event: ExceptionEvent::class, method: 'onKernelException')]
class ExceptionListener
{

    private const MESSAGE = 'Request has been failed';

    public function __construct(
        private readonly HttpCodeResolverInterface $codeResolver,
    ) {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $httpCode = $this->codeResolver->resolveCode($exception);

        $data = [
            'success' => false,
            'data' => [],
            'message' => self::MESSAGE,
            'http_code' => $httpCode,
            'errors' => [
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
            ],
            'total' => null,
        ];

        $event->setResponse(new JsonResponse($data, $data['http_code']));
    }

}
