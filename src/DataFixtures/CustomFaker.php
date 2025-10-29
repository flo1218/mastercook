<?php

namespace App\DataFixtures;

use Faker\Generator;
use FakerRestaurant\Provider\fr_FR\Restaurant;

/**
 * @method string foodName()
 * @method string fruitName()
 * @method string vegetableName()
 * @method string dairyName()
 * @method string meatName()
 */
class CustomFaker extends Generator
{
    public function __construct()
    {
        parent::__construct();
        $this->addProvider(new Restaurant($this));
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
        $method = $this->randomElement($this->getIngredientMethods());
        return $this->$method();
    }
}
