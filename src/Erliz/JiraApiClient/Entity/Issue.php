<?php

namespace Erliz\JiraApiClient\Entity;

use Closure;

/**
 * Issue.
 *
 * @author Stanislav Vetlovskiy <s.vetlovskiy@corp.mail.ru>
 */ 
class Issue extends CommonEntity
{
    /** @var string */
    private $key;
    /** @var IssueType */
    private $type;
    /** @var IssueStatus */
    private $status;
    /** @var Issue[] */
    private $subTasks;
    /** @var string */
    private $summary;
    /** @var string */
    private $description;
    /** @var IssueLink[] */
    private $links;
    /** @var \DateTime */
    private $createdAt;
    /** @var Project */
    private $project;
    /** @var Component[] */
    private $components;
    /** @var string[] */
    private $labels;
    /** @var Version[] */
    private $versions;
    /** @var User */
    private $assignee;
    /** @var User */
    private $reporter;
    /** @var Comment[] */
    private $comments;
    /** @var int */
    private $originalEstimate;
    /** @var int */
    private $remainingEstimate;
    /** @var Closure */
    private $fillIssueReference;
    /** @var bool */
    private $fullIssue = false;

    static public function getProjectKeyFromKey($issueKey)
    {
        return preg_replace('/(\-\d+)/', '', $issueKey);
    }

    public function isFullIssue()
    {
        return $this->fullIssue;
    }

    public function setFillIssueReference(Closure $fullIssueReference)
    {
        $this->fillIssueReference = $fullIssueReference;
    }

    private function fillIssue()
    {
        if($this->fullIssue){
            return;
        }
        call_user_func($this->fillIssueReference, $this);
        $this->fullIssue = true;
    }

    public function setProject($project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        if(empty($this->project)){
            $this->fillIssue();
        }

        return $this->project;
    }

    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function setType(IssueType $issueType)
    {
        $this->type = $issueType;

        return $this;
    }

    public function setStatus(IssueStatus $status)
    {
        $this->status = $status;

        return $this;
    }

    public function setComponents(array $components)
    {
        $this->components = $components;

        return $this;
    }

    public function addComponent(Component $component)
    {
        $this->components[] = $component;
        $this->components = array_unique($this->components);

        return $this;
    }

    public function setVersions(array $versions)
    {
        $this->versions = $versions;

        return $this;
    }

    public function addVersions(Version $version)
    {
        $this->versions[] = $version;
        $this->versions = array_unique($this->versions);

        return $this;
    }

    public function setAssignee(User $assignee)
    {
        $this->assignee = $assignee;

        return $this;
    }

    public function setReporter(User $reporter)
    {
        $this->reporter = $reporter;

        return $this;
    }

    public function setComments(array $comments)
    {
        $this->comments = $comments;

        return $this;
    }

    public function addComment(Comment $comment)
    {
        $this->comments[] = $comment;
        $this->comments = array_unique($this->comments);

        return $this;
    }

    public function setSubTasks(array $subTasks)
    {
        $this->subTasks = $subTasks;

        return $this;
    }

    public function addSubTasks(Issue $subTask)
    {
        $this->subTasks[] = $subTask;
        $this->subTasks = array_unique($this->subTasks);

        return $this;
    }

    public function getLinks()
    {
        return $this->links;
    }

    public function setLinks(array $links)
    {
        $this->links = $links;

        return $this;
    }

    public function addLink(IssueLink $link)
    {
        $this->links[] = $link;
        $this->links = array_unique($this->links);

        return $this;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return IssueType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return IssueStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return Issue[]
     */
    public function getSubTasks()
    {
        return $this->subTasks;
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return Component[]
     */
    public function getComponents()
    {
        return $this->components;
    }

    /**
     * @return string[]
     */
    public function getLabels()
    {
        if(empty($this->labels)){
            $this->fillIssue();
        }

        return $this->labels;
    }

    /**
     * @return Version[]
     */
    public function getVersions()
    {
        return $this->versions;
    }

    /**
     * @return User
     */
    public function getAssignee()
    {
        return $this->assignee;
    }

    /**
     * @return User
     */
    public function getReporter()
    {
        return $this->reporter;
    }

    /**
     * @return Comment[]
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param string $key
     *
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @param string $summary
     *
     * @return $this
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
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
     * @param string[] $labels
     *
     * @return $this
     */
    public function setLabels($labels)
    {
        $this->labels = $labels;

        return $this;
    }
}
