<?php

declare(strict_types=1);

namespace Http\Controller;

use Application\Entity\Challenge;
use Application\Entity\User;
use Application\InternalProtocol\Challenge as ChallengeDto;
use Application\Provider\ChallengeProviderInterface;
use Application\Service\ChallengeServiceInterface;
use Http\ArgumentResolver\PassChallengePayloadResolver;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
class ChallengeController extends BaseController
{

    public function __construct(
        private readonly ChallengeProviderInterface $challengeProvider,
        private readonly ChallengeServiceInterface $challengeService,
    ) {
    }

    #[Route('/users/{email}/challenges', name: 'get_challenges', methods: ['GET'])]
    public function getChallenges(
        #[MapEntity] User $user,
        #[MapQueryParameter] int $limit = 50,
        #[MapQueryParameter] int $offset = 0,
    ): Response
    {
        $challenges = $this->challengeService->getChallengesByUser($user, $limit, $offset);

        return $this->buildResponse(data: $challenges, total: \count($challenges));
    }

    #[Route('/users/{email}/challenges/{id}', name: 'get_challenge', methods: ['GET'])]
    public function getChallenge(
        #[MapEntity(mapping: ['email' => 'email'])] User $user,
        #[MapEntity(mapping: ['id' => 'id'])] Challenge $challenge
    ): Response
    {
        return $this->buildResponse(data: $challenge->asExtended());
    }

    #[Route('/users/{email}/challenges', name: 'activate_challenge', methods: ['POST'])]
    public function activateChallenge(
        #[MapEntity] User $user
    ): Response
    {
        $challenge = $this->challengeProvider->activateChallenge($user);

        return $this->buildResponse(data: $challenge->asPrepared(), total: 1);
    }

    #[Route('/users/{email}/challenges/{id}', name: 'pass_challenge', methods: ['PUT'])]
    public function passChallenge(
        #[MapEntity(mapping: ['email' => 'email'])] User $user,
        #[MapEntity(mapping: ['id' => 'id'])] Challenge $challenge,
        #[ValueResolver(PassChallengePayloadResolver::class)] ChallengeDto $passed
    ): Response
    {
        if (!$challenge->awaitingPassing()) {
            return $this->buildResponse(
                success: false,
                data: $challenge->asPassed(),
                message: 'Challenge was passed already',
            );
        }

        $this->challengeProvider->passChallenge($user, $challenge, $passed);

        return $this->buildResponse(data: $challenge->asPassed());
    }

}
