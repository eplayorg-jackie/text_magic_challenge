<?php

declare(strict_types=1);

namespace Http\ArgumentResolver;

use Application\Exception\ValidationException;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestPayloadValueResolver as SymfonyResolver;
use Symfony\Component\HttpKernel\Event\ControllerArgumentsEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class RequestPayloadValueResolver extends SymfonyResolver
{

    /**
     * @throws HttpExceptionInterface
     * @throws ValidationException
     */
    public function onKernelControllerArguments(ControllerArgumentsEvent $event): void
    {
        try {
            parent::onKernelControllerArguments($event);
        } catch (HttpExceptionInterface $exception) {
            $previous = $exception->getPrevious();

            if ($previous instanceof ValidationFailedException) {
                throw ValidationException::createFromViolationList($previous->getViolations());
            }

            throw $exception;
        } catch (\Throwable $exception) {
            throw new ValidationException([], $exception->getMessage());
        }
    }

}
