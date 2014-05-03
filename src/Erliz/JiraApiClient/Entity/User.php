<?php

namespace Erliz\JiraApiClient\Entity;

/**
 * User.
 *
 * @author Stanislav Vetlovskiy <s.vetlovskiy@corp.mail.ru>
 */ 
class User extends CommonEntity
{
    /** @var string */
    private $name;
    /** @var string */
    private $email;
    /** @var string */
    private $avatarUrl;
    /** @var bool */
    private $active;

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

    /**
     * @return string
     */
    public function getAvatarUrl()
    {
        return $this->avatarUrl;
    }

    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email)
    {;
        $this->email = $email;

        return $this;
    }

    /**
     * @param string $avatarUrl
     *
     * @return $this
     */
    public function setAvatarUrl($avatarUrl)
    {
        $this->avatarUrl = $avatarUrl;

        return $this;
    }

    /**
     * @param boolean $isActive
     *
     * @return $this
     */
    public function setActive($isActive)
    {
        $this->active = $isActive;

        return $this;
    }
}
