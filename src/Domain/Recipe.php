<?php

namespace CocktailRater\Domain;

final class Recipe
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

    /** @var string */
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
    public static function fromStorageArray(array $data)
    {
        $ingredients = array_map(function ($ingredient) {
            return new MeasuredIngredient(
                new Ingredient($ingredient['name']),
                Amount::fromValues($ingredient['quantity'], $ingredient['units'])
            );
        }, $data['measured_ingredients']);

        $recipe = new self(
            new RecipeName($data['name']),
            new User(new Username($data['user'])),
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

    /*
    public function getRating()
    {
        return $this->rating;
    }
     */

    public function hasNameAndUser(RecipeName $name, User $user)
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
            'user'                 => $this->user->view()['name'],
            'stars'                => $this->rating->getValue(),
            'measured_ingredients' => $this->ingredients->view(),
            'method'               => $this->method->getValue()
        ];
    }
}
