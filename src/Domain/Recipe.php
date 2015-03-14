<?php

namespace CocktailRater\Domain;

class Recipe
{
    /** @var string */
    private $name;

    /** @var Stars */
    private $rating;

    /** @var User */
    private $user;

    public function __construct($name, User $user, Stars $rating)
    {
        $this->name = $name;
        $this->user = $user;
        $this->rating = $rating;
    }

    public function getRating()
    {
        return $this->rating;
    }

    public function hasNameAndUser($name, User $user)
    {
        return $name == $this->name && $user == $this->user;
    }

    public function isHigherRatedThan(self $other)
    {
        return $this->rating->isHigherRatedThan($other->rating);
    }
}
