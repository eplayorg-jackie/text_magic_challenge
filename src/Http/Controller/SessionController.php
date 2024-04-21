<?php

declare(strict_types=1);

namespace Http\Controller;

use Application\Entity\User;
use Application\Service\UserServiceInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class SessionController extends BaseController
{

    public function __construct(
        private readonly UserServiceInterface $userService
    ) {

    }

    #[Route('/sessions/{email}', name: 'get_session', methods: ['GET'])]
    public function getSession(
        #[MapEntity(mapping: ['email' => 'email'])] User $user
    ): Response
    {
        return $this->buildResponse(
            data: $user->asArray(),
            message: 'Actual user session'
        );
    }

    #[Route('/sessions/{email}', name: 'activate_session', methods: ['POST'])]
    public function activateSession(Request $request, string $email): Response
    {
        $user = $this->userService->createIfNotExists($email);

        return $this->buildResponse(
            data: $user->asArray(),
            message: 'User session has been started'
        );
    }

}
