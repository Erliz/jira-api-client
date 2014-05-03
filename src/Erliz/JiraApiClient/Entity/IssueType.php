<?php

namespace Erliz\JiraApiClient\Entity;

/**
 * IssueType.
 *
 * @author Stanislav Vetlovskiy <s.vetlovskiy@corp.mail.ru>
 */ 
class IssueType extends CommonEntity
{
    /** @var string */
    private $name;
    /** @var string */
    private $description;
    /** @var bool */
    private $subTask;
    /** @var string */
    private $iconUrl;

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
    public function isSubTask()
    {
        return $this->subTask;
    }

    /**
     * @param boolean $subTask
     *
     * @return $this
     */
    public function setSubTask($subTask)
    {
        $this->subTask = (bool) $subTask;

        return $this;
    }

    /**
     * @return string
     */
    public function getIconUrl()
    {
        return $this->iconUrl;
    }

    /**
     * @param string $iconUrl
     *
     * @return $this
     */
    public function setIconUrl($iconUrl)
    {
        $this->iconUrl = $iconUrl;

        return $this;
    }
}
