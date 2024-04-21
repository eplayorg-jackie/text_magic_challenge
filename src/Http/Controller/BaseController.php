<?php

declare(strict_types=1);

namespace Http\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BaseController
 * @package Http\Controller
 */
class BaseController extends AbstractController
{

    protected function buildResponse(
        bool $success = true,
        ?array $data = null,
        string $message = '',
        int $httpCode = Response::HTTP_OK,
        int $total = 0,
    ): JsonResponse {
        return $this->json([
            'success' => $success,
            'data' => $this->resolveData($data),
            'message' => $message,
            'http_code' => $httpCode,
            'total' => $total,
        ], $httpCode);
    }

    private function resolveData(mixed $data = null): array
    {
        if (empty($data)) {
            $data = [];
        }

        if (!\is_array($data)) {
            $data = (array) $data;
        }

        return $data;
    }

}
