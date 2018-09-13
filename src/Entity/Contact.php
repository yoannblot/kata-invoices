<?php

namespace Kata\Entity;

final class Contact
{
    /** @var \string */
    private $name;

    /** @var \string */
    private $email;

    /**
     * Contact constructor.
     *
     * @param string $name
     * @param string $email
     */
    public function __construct($name, $email)
    {
        $this->name  = $name;
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

}
