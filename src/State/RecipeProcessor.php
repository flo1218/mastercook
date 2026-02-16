<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Recipe;
use App\Entity\User;
use App\Exception\DuplicateException;
use App\Repository\RecipeRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * @implements ProcessorInterface<Recipe, mixed>
 */
final class RecipeProcessor implements ProcessorInterface
{
    /**
     * @param ProcessorInterface<Recipe, mixed> $persistProcessor
     */
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        private RecipeRepository $recipeRepository,
        private Security $security,
    ) {
        // Constructor body can be empty
    }

    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = [],
    ): mixed {
        /** @var Recipe $data */
        if ($data->getTime() > 1440) {
            throw new \Exception('Time must be < 1440');
        }

        /** @var User|null $user */
        $user = $this->security->getUser();
        if (null === $user) {
            throw new \Exception('User must be authenticated');
        }

        $data = $data->setUser($user);
        $name = $data->getName();
        if (null === $name) {
            throw new \Exception('Recipe name cannot be null');
        }
        $userId = $user->getId();
        // User is not null, so ID should not be null either
        /** @var int $userId */
        $response = $this->recipeRepository->findDuplicateRecipe($userId, $name);
        if (0 == count($response)) {
            $result = $this->persistProcessor->process($data, $operation, $uriVariables, $context);

            return $result;
        }

        throw new DuplicateException('Duplicate Recipe detected');
    }
}
