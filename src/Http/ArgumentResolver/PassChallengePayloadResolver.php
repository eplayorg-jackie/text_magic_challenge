<?php

declare(strict_types=1);

namespace Http\ArgumentResolver;

use Application\Exception\InvalidRequestException;
use Application\InternalProtocol\Challenge;
use Application\InternalProtocol\Embedded\ChallengeItem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Uid\Uuid;

class PassChallengePayloadResolver implements ValueResolverInterface
{

    /**
     * @throws InvalidRequestException
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $payload = \json_decode($request->getContent(), true);
        if (empty($payload['items'])) {
            throw new InvalidRequestException("Request has incorrect format");
        }

        $items = [];
        $invalid = [];
        foreach ($payload['items'] as $index => $item) {
            $itemId = $item['id'] ?? null;
            $questionId = $item['questionId'] ?? null;
            if (empty($itemId) || empty($questionId)) {
               $invalid[] = $index;
               continue;
            }

            $items[] = new ChallengeItem(
                Uuid::fromString($itemId), Uuid::fromString($questionId)
            );
        }

        if (\count($invalid)) {
            throw new InvalidRequestException(\sprintf(
                'Request payload has invalid rows: %s', \implode(', ', $invalid)
            ));
        }

        return [new Challenge($items)];
    }

}
