<?php

namespace Erliz\JiraApiClient\Entity;

/**
 * IssueLink.
 *
 * @author Stanislav Vetlovskiy <s.vetlovskiy@corp.mail.ru>
 */
class IssueLink extends CommonEntity
{
    /** @var IssueLinkType */
    private $type;
    /** @var Issue */
    private $issue;
    /** @var bool */
    private $inward;

    /**
     * @return bool
     */
    public function isInward()
    {
        return $this->inward;
    }

    /**
     * @return IssueLinkType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return Issue
     */
    public function getIssue()
    {
        return $this->issue;
    }

    /**
     * @param Issue $issue
     *
     * @return $this
     */
    public function setInwardIssue(Issue $issue)
    {
        $this->issue = $issue;
        $this->inward = true;

        return $this;
    }

    /**
     * @param Issue $issue
     *
     * @return $this
     */
    public function setOutwardIssue(Issue $issue)
    {
        $this->issue = $issue;
        $this->inward = false;

        return $this;
    }

    /**
     * @param IssueLinkType $type
     *
     * @return $this
     */
    public function setType(IssueLinkType $type)
    {
        $this->type = $type;

        return $this;
    }
}
