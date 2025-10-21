<?php

namespace App\State;

use App\Entity\User;
use App\Entity\Recipe;
use ApiPlatform\Metadata\Operation;
use App\Exception\DuplicateException;
use App\Repository\RecipeRepository;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class RecipeProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface $persistProcessor,
        #[Autowire(service: 'api_platform.doctrine.orm.state.remove_processor')]
        private ProcessorInterface $removeProcessor,
        private RecipeRepository $recipeRepository,
        private Security $security
    ) {}

    /**
     * @return void
     */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ) {
        /** @var Recipe $data */
        if ($data->getTime() > 1440) {
            throw new \Exception('Time must be < 1440');
        }

        /** @var User $user */
        $user = $this->security->getUser();
        $data = $data->setUser($user);
        $response = $this->recipeRepository->findDuplicateRecipe($user->getId(), $data->getName());
        if (0 == count($response)) {
            $result = $this->persistProcessor->process($data, $operation, $uriVariables, $context);

            return $result;
        }

        throw new DuplicateException('Duplicate Recipe detected');
    }
}
