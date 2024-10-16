<?php

namespace App\State;

use Exception;
use App\Entity\Recipe;
use App\Dto\UserResetPasswordDto;
use ApiPlatform\Metadata\Operation;
use App\Exception\DuplicateException;
use App\Repository\ViewRecipeRepository;
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
        private ViewRecipeRepository $recipeRepository,
        private Security $security
    ) {
    }

    /**
     *
     * @param mixed $data
     * @param Operation $operation
     * @param array $uriVariables
     * @param array $context
     * @return void
     */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ) {
        /** @var Recipe $data **/
        if ($data->getTime() > 1440) {
            throw new DuplicateException('Time must be < 1440');
        }
        $user = $this->security->getUser();
        /**
         * @var \App\Entity\User $user
         */
        $data = $data->setUser($user);
        $response = $this->recipeRepository->findDuplicateRecipe($user->getId(), $data->getName());
        if (count($response) == 0) {
            $result = $this->persistProcessor->process($data, $operation, $uriVariables, $context);
            return $result;
        }

        throw new DuplicateException('Duplicate Recipe detected');
    }
}
