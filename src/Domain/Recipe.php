<?php

namespace CocktailRater\Domain;

use Assert\Assertion;

final class Recipe
{
    /** @var string */
    private $name;

    /** @var Stars */
    private $rating;

    /** @var User */
    private $user;

    /** @var MeasuredIngredientList[] */
    private $ingredients;

    /** @var string */
    private $method;

    public function __construct(
        $name,
        User $user,
        Stars $rating,
        MeasuredIngredientList $ingredients,
        Method $method
    ) {
        Assertion::string($name);

        $this->name        = $name;
        $this->user        = $user;
        $this->rating      = $rating;
        $this->ingredients = $ingredients;
        $this->method      = $method;
    }

    /*
    public function getRating()
    {
        return $this->rating;
    }
     */

    public function hasNameAndUser($name, User $user)
    {
        return $name == $this->name && $user == $this->user;
    }

    /*
    public function isHigherRatedThan(self $other)
    {
        return $this->rating->isHigherRatedThan($other->rating);
    }
     */

    /** @return array */
    public function view()
    {
        return [
            'name'                 => $this->name,
            'user'                 => $this->user->view(),
            'stars'                => $this->rating->getValue(),
            'measured_ingredients' => $this->ingredients->view(),
            'method'               => $this->method->getValue()
        ];
    }
}
