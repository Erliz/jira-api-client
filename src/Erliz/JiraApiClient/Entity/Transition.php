<?php
/**
 * @author Stanislav Vetlovskiy
 * @date   06.05.14
 */

namespace Erliz\JiraApiClient\Entity;


class Transition extends CommonEntity
{
    /** @var string */
    private $name;
    /** @var IssueStatus */
    private $status;

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
     * @return IssueStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param IssueStatus $status
     *
     * @return $this
     */
    public function setStatus(IssueStatus $status)
    {
        $this->status = $status;

        return $this;
    }
}
