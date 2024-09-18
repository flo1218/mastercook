<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Mark;
use App\Entity\User;
use Faker\Generator;
use App\Entity\Recipe;
use DateTimeImmutable;
use App\Entity\Contact;
use App\Entity\Ingredient;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
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
        // Users
        $users = [];

        $admin = new User();
        $admin->setFullName('Admin')
            ->setPseudo('Admin')
            ->setLanguage('fr')
            ->setEmail('admin@mastercook.ch')
            ->setRoles(['ROLE_USER', 'ROLE_ADMIN'])
            ->setPlainPassword('password');

        $users[] = $admin;
        $manager->persist($admin);

        for ($i = 0; $i < 9; ++$i) {
            $user = new User();
            $user->setFullName($this->faker->name())
                ->setPseudo($this->faker->boolean() ? $this->faker->firstName() : null)
                ->setEmail($this->faker->email())
                ->setLanguage('en')
                ->setRoles(['ROLE_USER'])
                ->setPlainPassword('password');

            $users[] = $user;
            $manager->persist($user);
        }

        // Ingredients
        $ingredientFakerList = [
            'dairyName',
            'vegetableName',
            'fruitName',
            'meatName'
        ];

        $ingredients = [];
        for ($i = 1; $i <= 50; ++$i) {
            $method = $ingredientFakerList[mt_rand(0, 3)];
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->$method())
                ->setPrice(mt_rand(0, 100))
                ->setUser($users[mt_rand(0, count($users) - 1)]);

            $ingredients[] = $ingredient;
            $manager->persist($ingredient);
        }

        // Recipes
        $dateTimeImmutable = new DateTimeImmutable();
        for ($j = 1; $j <= 200; ++$j) {
            $recipe = new Recipe();
            $user = $users[mt_rand(0, count($users) - 1)];
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

            for ($k = 1; $k < mt_rand(5, 15); ++$k) {
                $recipe->addIngredient($ingredients[mt_rand(0, count($ingredients) - 1)]);
            }
            $recipes[] = $recipe;
            $manager->persist($recipe);
        }

        // Marks
        foreach ($recipes as $recipe) {
            for ($i = 0; $i < mt_rand(0, 4); ++$i) {
                $mark = new Mark();
                $mark->setMark(mt_rand(1, 5))
                    ->setUser($users[mt_rand(0, count($users) - 1)])
                    ->setRecipe($recipe);

                $manager->persist($mark);
            }
        }

        // Contact
        for ($i = 0; $i < 5; ++$i) {
            $contact = new Contact();
            $contact->setFullName($this->faker->name())
                ->setEmail($this->faker->email())
                ->setSubject('Demande n°' . ($i + 1))
                ->setMessage($this->faker->text());

            $manager->persist($contact);
        }

        $manager->flush();
    }
}
