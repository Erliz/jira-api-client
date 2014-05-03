<?php

/**
 * @author Stanislav Vetlovskiy
 * @date 02.05.14
 */

namespace Erliz\JiraApiClient\Entity;


abstract class CommonEntity
{
    /** @var int|string */
    private $id;

    /**
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int|string $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function __toString()
    {
        return get_class($this) . '_' . $this->getId();
    }
} 