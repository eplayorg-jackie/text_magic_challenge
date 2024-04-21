<?php

declare(strict_types=1);

namespace Application\Exception;

use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Class ValidationException
 * @package Petrosoft\ExceptionsProcessingBundle\Exception
 */
class ValidationException extends \Exception
{

    public function __construct(
        private readonly array $violations,
        string $message = 'Application validation error',
        int $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array
     */
    public function getViolations(): array
    {
        return $this->violations;
    }

    /**
     * @param ConstraintViolationListInterface $violationList
     * @return ValidationException
     */
    public static function createFromViolationList(ConstraintViolationListInterface $violationList): ValidationException
    {
        $errors = [];
        $fields = [];

        /**
         * @var $violation ConstraintViolationInterface
         */
        foreach ($violationList as $violation) {
            $errors[] = [
                'field' => $violation->getPropertyPath(),
                'message' => $violation->getMessage()
            ];

            $fields[] = $violation->getPropertyPath();
        }

        return new ValidationException(
            $errors,
            \sprintf('Application validation error. Invalid fields: %s', \implode(', ', $fields)),
            400,
            null
        );
    }

}
