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
    /** @var IssuePriority */
    private $priority;
    /** @var Issue */
    private $parent;
    /** @var Issue[] */
    private $subTasks;
    /** @var string */
    private $summary;
    /** @var string */
    private $description;
    /** @var Transition[] */
    private $transitions;
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
    /** @var Resolution */
    private $resolution;
    /** @var \DateTime */
    private $resolvedAt;
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

    private function isFullIssue()
    {
        return $this->fullIssue;
    }

    public function setFillIssueReference(Closure $fullIssueReference)
    {
        $this->fillIssueReference = $fullIssueReference;
    }

    private function fillIssue()
    {
        if ($this->isFullIssue()) {
            return;
        }
        call_user_func($this->fillIssueReference, $this);
        $this->fullIssue = true;
    }

    /**
     * @param Project $project
     *
     * @return $this
     */
    public function setProject(Project $project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        if (empty($this->project)) {
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
        if (empty($this->links)) {
            $this->fillIssue();
        }

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
        if (empty($this->subTasks)) {
            $this->fillIssue();
        }

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
        if (empty($this->description)) {
            $this->fillIssue();
        }

        return $this->description;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        if (empty($this->createdAt)) {
            $this->fillIssue();
        }

        return $this->createdAt;
    }

    /**
     * @return Component[]
     */
    public function getComponents()
    {
        if (empty($this->components)) {
            $this->fillIssue();
        }

        return $this->components;
    }

    /**
     * @return string[]
     */
    public function getLabels()
    {
        if (empty($this->labels)) {
            $this->fillIssue();
        }

        return $this->labels;
    }

    /**
     * @return Version[]
     */
    public function getVersions()
    {
        if (empty($this->versions)) {
            $this->fillIssue();
        }

        return $this->versions;
    }

    /**
     * @return User
     */
    public function getAssignee()
    {
        if (empty($this->assignee)) {
            $this->fillIssue();
        }

        return $this->assignee;
    }

    /**
     * @return User
     */
    public function getReporter()
    {
        if (empty($this->reporter)) {
            $this->fillIssue();
        }

        return $this->reporter;
    }

    /**
     * @return Comment[]
     */
    public function getComments()
    {
        if (empty($this->comments)) {
            $this->fillIssue();
        }

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
    public function setLabels(array $labels)
    {
        // reset array key
        $this->labels = array_values($labels);

        return $this;
    }

    /**
     * @param string $labels
     *
     * @return $this
     */
    public function addLabel($labels)
    {
        $this->labels[] = $labels;
        $this->labels = array_unique($this->labels);

        return $this;
    }

    /**
     * @return IssuePriority
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param IssuePriority $priority
     *
     * @return $this
     */
    public function setPriority(IssuePriority $priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @return Issue
     */
    public function getParent()
    {
        if (empty($this->parent) && $this->type->isSubTask()) {
            $this->fillIssue();
        }

        return $this->parent;
    }

    /**
     * @param Issue $parent
     *
     * @return $this
     */
    public function setParent(Issue $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Resolution
     */
    public function getResolution()
    {
        if (empty($this->resolution)) {
            $this->fillIssue();
        }

        return $this->resolution;
    }

    /**
     * @param Resolution $resolution
     *
     * @return $this
     */
    public function setResolution(Resolution $resolution)
    {
        $this->resolution = $resolution;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getResolvedAt()
    {
        if (empty($this->resolvedAt)) {
            $this->fillIssue();
        }

        return $this->resolvedAt;
    }

    /**
     * @param \DateTime $resolvedAt
     *
     * @return $this
     */
    public function setResolvedAt(\DateTime $resolvedAt)
    {
        $this->resolvedAt = $resolvedAt;

        return $this;
    }

    /**
     * @return Transition[]
     */
    public function getTransitions()
    {
        if (empty($this->transitions)) {
            $this->fillIssue();
        }

        return $this->transitions;
    }

    /**
     * @param Transition[] $transitions
     *
     * @return $this
     */
    public function setTransitions(array $transitions)
    {
        $this->transitions = $transitions;

        return $this;
    }

    /**
     * @param string $key transition id or name
     *
     * @return bool
     */
    public function haveTransition($key)
    {
        return !!$this->getTransition($key);
    }

    /**
     * @param string $key transition id or name
     *
     * @return Transition
     */
    public function getTransition($key)
    {
        $isId = false;
        if (is_numeric($key)) {
            $isId = true;
        }
        $findTransition = false;

        foreach ($this->getTransitions() as $transition) {
            if($isId && $transition->getId() == $key) {
                $findTransition = $transition;
                break;
            } elseif (!$isId && $transition->getName() == $key) {
                $findTransition = $transition;
                break;
            }
        }

        return $findTransition;
    }
}
