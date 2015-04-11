<?php

namespace CocktailRater\Domain;

final class User implements Authenticated
{
    /** @var UserId */
    private $id;

    /** @var Username */
    private $username;

    /** @return self */
    public static function fromStorageArray(array $values)
    {
        $user = new self(new Username($values['name']));

        $user->setId(new UserId($values['id']));

        return $user;
    }

    public function __construct(Username $username)
    {
        $this->username = $username;
    }

    /** @return UserId */
    public function getId()
    {
        return $this->id;
    }

    public function setId(UserId $id)
    {
        $this->id = $id;
    }

    public function isAuthenticatedBy(Username $username, Password $password)
    {
        return $username == $this->username;
    }

    /** @return array */
    public function view()
    {
        return ['name' => $this->username->getValue()];
    }

    /** @return array */
    public function getForStorage()
    {
        return [
            'id'   => $this->id->getValue(),
            'name' => $this->username->getValue()
        ];
    }
}
