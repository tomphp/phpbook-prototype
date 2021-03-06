<?php

namespace CocktailRater\Domain;

use Assert\Assertion;

final class Recipe implements NamedRecipe, UserOwned
{
    /** @var RecipeId|null */
    private $id;

    /** @var RecipeName */
    private $name;

    /** @var Stars */
    private $rating;

    /** @var User */
    private $user;

    /** @var MeasuredIngredientList[] */
    private $ingredients;

    /** @var Method */
    private $method;

    /** @return Recipe */
    public static function withNoId(
        RecipeName $name,
        User $user,
        Stars $rating,
        MeasuredIngredientList $ingredients,
        Method $method
    ) {
        return new self(
            $name,
            $user,
            $rating,
            $ingredients,
            $method
        );
    }

    /** @return Recipe */
    public static function fromStorageArray(array $data, User $user)
    {
        $ingredients = array_map(function ($ingredient) {
            return new MeasuredIngredient(
                new Ingredient($ingredient['name']),
                Amount::fromValues($ingredient['quantity'], $ingredient['units'])
            );
        }, $data['measured_ingredients']);

        $recipe = new self(
            new RecipeName($data['name']),
            $user,
            new Stars($data['stars']),
            new MeasuredIngredientList($ingredients),
            new Method($data['method'])
        );

        $recipe->setId(new RecipeId($data['id']));

        return $recipe;
    }

    public function __construct(
        RecipeName $name,
        User $user,
        Stars $rating,
        MeasuredIngredientList $ingredients,
        Method $method
    ) {
        $this->name        = $name;
        $this->user        = $user;
        $this->rating      = $rating;
        $this->ingredients = $ingredients;
        $this->method      = $method;
    }

    /** @return RecipeId|null */
    public function getId()
    {
        return $this->id;
    }

    public function setId(RecipeId $id)
    {
        $this->id = $id;
    }

    public function hasNameMatching($name)
    {
        return $name == $this->name;
    }

    public function isOwnedByUser(User $user)
    {
        return $this->user == $user;
    }

    /*
    public function getRating()
    {
        return $this->rating;
    }
     */

    /*
    public function isHigherRatedThan(self $other)
    {
        return $this->rating->isHigherRatedThan($other->rating);
    }
     */

    /** @return array */
    public function view()
    {
       return array_merge(
           $this->id ? ['id' => $this->id->getValue()] : [],
           [
               'name'                 => $this->name->getValue(),
               'user'                 => $this->user->view(),
               'stars'                => $this->rating->getValue(),
               'measured_ingredients' => $this->ingredients->view(),
               'method'               => $this->method->getValue()
           ]
       );
    }

    /** @return array */
    public function getForStorage()
    {
        return [
            'id'                   => $this->id->getValue(),
            'name'                 => $this->name->getValue(),
            'user'                 => $this->user->getId()->getValue(),
            'stars'                => $this->rating->getValue(),
            'measured_ingredients' => $this->ingredients->view(),
            'method'               => $this->method->getValue()
        ];
    }
}
