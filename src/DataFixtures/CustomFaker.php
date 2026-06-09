<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use FakerRestaurant\Provider\fr_FR\Restaurant;

/**
 * @method string foodName()
 * @method string fruitName()
 * @method string vegetableName()
 * @method string dairyName()
 * @method string meatName()
 */
class CustomFaker
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
        $this->faker->addProvider(new Restaurant($this->faker));
    }

    public function faker(): Generator
    {
        return $this->faker;
    }

    /**
     * @return string[]
     */
    public function getIngredientMethods(): array
    {
        return [
            'dairyName',
            'vegetableName',
            'fruitName',
            'meatName',
        ];
    }

    public function randomIngredientName(): string
    {
        $method = $this->faker->randomElement($this->getIngredientMethods());
        $value = $this->faker->$method();

        return is_string($value) ? $value : '';
    }
}
