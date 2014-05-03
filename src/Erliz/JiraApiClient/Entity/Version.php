<?php

namespace Erliz\JiraApiClient\Entity;

/**
 * Version.
 *
 * @author Stanislav Vetlovskiy <s.vetlovskiy@corp.mail.ru>
 */ 
class Version extends CommonEntity
{
    /** @var string */
    private $name;
    /** @var string */
    private $description;
    /** @var bool */
    private $archived;
    /** @var bool */
    private $released;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isArchived()
    {
        return $this->archived;
    }

    /**
     * @param boolean $archived
     *
     * @return $this
     */
    public function setArchived($archived)
    {
        $this->archived = $archived;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isReleased()
    {
        return $this->released;
    }

    /**
     * @param boolean $released
     *
     * @return $this
     */
    public function setReleased($released)
    {
        $this->released = $released;

        return $this;
    }
}
