<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use App\Entity\Ingredient;
use App\Entity\Mark;
use App\Entity\Recipe;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $factory = new Factory();
        $this->faker = $factory::create('fr_FR');
        $this->faker->addProvider(new \FakerRestaurant\Provider\fr_FR\Restaurant($this->faker));
        $this->hasher = $hasher;
    }

    private UserPasswordHasherInterface $hasher;

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setFullName('Admin')
            ->setPseudo('Admin')
            ->setLanguage('fr')
            ->setEmail('admin@mastercook.ch')
            ->setRoles(['ROLE_USER', 'ROLE_ADMIN'])
            ->setPlainPassword('password');

        $manager->persist($admin);

        for ($i = 0; $i < 5; ++$i) {
            $user = new User();
            $user->setFullName($this->faker->name())
                ->setPseudo($this->faker->boolean() ? $this->faker->firstName() : null)
                ->setEmail($this->faker->email())
                ->setLanguage('fr')
                ->setRoles(['ROLE_USER'])
                ->setPlainPassword('password');

            $users[] = $user;
            $manager->persist($user);

            // Ingredients
            $ingredientFakerList = [
                'dairyName',
                'vegetableName',
                'fruitName',
                'meatName',
            ];

            $ingredients = [];
            for ($j = 1; $j <= 10; ++$j) {
                $method = $ingredientFakerList[mt_rand(0, 3)];
                $ingredient = new Ingredient();
                $ingredient->setName(name: $this->faker->$method())
                    ->setPrice(mt_rand(0, 100))
                    ->setUser($user);
                if (!in_array($ingredient->getName(), $ingredients)) {
                    $ingredients[] = $ingredient;
                }
                $manager->persist($ingredient);
            }

            // Recipes
            $dateTimeImmutable = new \DateTimeImmutable();
            for ($k = 1; $k <= 3; ++$k) {
                $recipe = new Recipe();
                $recipe->setName($this->faker->foodName())
                    ->setTime(mt_rand(1, 1440))
                    ->setPrice(mt_rand(1, 1000))
                    ->setNbPeople($this->faker->boolean() ? mt_rand(1, 10) : null)
                    ->setDifficulty($this->faker->boolean() ? mt_rand(1, 5) : null)
                    ->setDescription($this->faker->paragraphs(3, true))
                    ->setIsFavorite($this->faker->boolean())
                    ->setIsPublic($this->faker->boolean())
                    ->setCreatedAt(
                        $dateTimeImmutable::createFromMutable($this->faker->dateTimeBetween($startDate = '-3 years'))
                    )
                    ->setCreatedBy($user->getFullName())
                    ->setUser($user);

                for ($l = 1; $l < mt_rand(5, 10); ++$l) {
                    $recipe->addIngredient($ingredients[mt_rand(0, count($ingredients) - 1)]);
                }
                $recipes[] = $recipe;
                $manager->persist($recipe);
            }

            // Marks
            foreach ($recipes as $recipe) {
                for ($m = 0; $m < mt_rand(0, 4); ++$m) {
                    $mark = new Mark();
                    $mark->setMark(mt_rand(1, 5))
                        ->setUser($user)
                        ->setRecipe($recipe);

                    $manager->persist($mark);
                }
            }
        }

        // Contact
        for ($n = 1; $n < 6; ++$n) {
            $contact = new Contact();
            $contact->setFullName($this->faker->name())
                ->setEmail($this->faker->email())
                ->setSubject('Demande n°' . ($n))
                ->setMessage($this->faker->text());

            $manager->persist($contact);
        }

        $manager->flush();
    }
}
