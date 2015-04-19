<?php

namespace CocktailRater\Domain;

final class ProspectiveUser
{
    /** @var Username */
    private $username;

    /** @var Email */
    private $email;

    /**
     * @param string $username
     * @param string $email
     * @param string $password
     *
     * @return self
     */
    public static function fromValues($username, $email, $password)
    {
        return new ProspectiveUser(
            new Username($username),
            new Email($email),
            new Password($password)
        );
    }

    public function __construct(
        Username $username,
        Email $email,
        Password $password
    ) {
        $this->username = $username;
        $this->email    = $email;
    }

    /**
     * @todo RegisteredUser?
     *
     * @return User
     */
    public function convertToUser()
    {
        return new User($this->username, $this->email);
    }

    /** @return Username */
    public function getUsername()
    {
        return $this->username;
    }
}
