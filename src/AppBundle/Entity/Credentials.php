<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
class Credentials
{
    /**
     * @Assert\NotBlank()
     * @Assert\Type("string")
     */
    private $login;

    /**
     * @Assert\NotBlank()
     * @Assert\Type("string")
     */
    private $password;


    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }
}

