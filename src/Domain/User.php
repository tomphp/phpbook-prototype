<?php

namespace CocktailRater\Domain;

final class User implements Authenticated
{
    /** @var UserId */
    private $id;

    /** @var Username */
    private $username;

    /** @var Email */
    private $email;

    /** @return self */
    public static function fromStorageArray(array $values)
    {
        $user = new self(
            new Username($values['username']),
            new Email($values['email'])
        );

        $user->setId(new UserId($values['id']));

        return $user;
    }

    public function __construct(Username $username, Email $email)
    {
        $this->username = $username;
        $this->email    = $email;
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
        return [
            'username' => $this->username->getValue(),
            'email'    => $this->email->getValue(),
        ];
    }

    /** @return array */
    public function getForStorage()
    {
        return [
            'id'       => $this->id->getValue(),
            'username' => $this->username->getValue(),
            'email'    => $this->email->getValue(),
        ];
    }

    /** @return bool */
    public function isSameAs(self $other)
    {
        return $this->id == $other->id;
    }
}
