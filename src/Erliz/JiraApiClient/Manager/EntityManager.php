<?php

/**
 * @author Stanislav Vetlovskiy
 * @date 02.05.14
 */

namespace Erliz\JiraApiClient\Manager;

use Erliz\JiraApiClient\Api\Client;
use Erliz\JiraApiClient\Entity\Comment;
use Erliz\JiraApiClient\Entity\CommonEntity;
use Erliz\JiraApiClient\Entity\Component;
use Erliz\JiraApiClient\Entity\Issue;
use Erliz\JiraApiClient\Entity\IssueLink;
use Erliz\JiraApiClient\Entity\IssueLinkType;
use Erliz\JiraApiClient\Entity\IssuePriority;
use Erliz\JiraApiClient\Entity\IssueStatus;
use Erliz\JiraApiClient\Entity\IssueType;
use Erliz\JiraApiClient\Entity\Project;
use Erliz\JiraApiClient\Entity\Resolution;
use Erliz\JiraApiClient\Entity\User;
use Erliz\JiraApiClient\Entity\Version;

class EntityManager
{
    const COMPONENT_CACHE_KEY       = 'component';
    const COMMENT_CACHE_KEY         = 'comment';
    const ISSUE_CACHE_KEY           = 'issue';
    const ISSUE_LINK_CACHE_KEY      = 'issue_link';
    const ISSUE_LINK_TYPE_CACHE_KEY = 'issue_link_type';
    const ISSUE_PRIORITY_CACHE_KEY  = 'issue_priority';
    const ISSUE_STATUS_CACHE_KEY    = 'issue_status';
    const ISSUE_TYPE_CACHE_KEY      = 'issue_type';
    const PROJECT_CACHE_KEY         = 'project';
    const RESOLUTION_CACHE_KEY      = 'resolution';
    const USER_CACHE_KEY            = 'user';
    const VERSION_CACHE_KEY         = 'version';

    /** @var array */
    private $cache = array();
    /** @var Client */
    private $client;

    /**
     * Can cause recursion and long load of linked issues chain
     *
     * @param Client $client
     *
     * @return $this
     */
    public function setApiClient(Client $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @param \stdClass $data
     *
     * @return Issue
     */
    public function newIssue(\stdClass $data)
    {
        $issue = $this->getFromCache($this::ISSUE_CACHE_KEY, $data) ? : new Issue();

        if (empty($data) || $issue->getId()) {
            return $issue;
        }

        $issue->setFillIssueReference($this->fillIssueReferenceCloser());
        $issue = $this->fillIssueWithData($issue, $data);

        $this->addToCache($this::ISSUE_CACHE_KEY, $issue);

        return $issue;
    }

    /**
     * @param Issue     $issue
     * @param \stdClass $data
     *
     * @return Issue
     */
    public function fillIssueWithData(Issue $issue, \stdClass $data)
    {
        $issue->setId($data->id)
              ->setKey($data->key);

        $fields = $data->fields;

        if(isset($fields->description)) {
            $issue->setDescription($fields->description);
        }

        $issue->setSummary($fields->summary)
              ->setStatus($this->newIssueStatus($fields->status))
              ->setType($this->newIssueType($fields->issuetype));

        if(isset($fields->project)) {
            $issue->setProject($this->newProject($fields->project));
        } else {
            $project = $this->getProjectFromCacheByKey(Issue::getProjectKeyFromKey($data->key));
            if ($project) {
                $issue->setProject($project);
            }
        }

        if(isset($fields->parent)) {
            $issue->setParent($this->newIssue($fields->parent));
        }
        if(isset($fields->labels)) {
            $issue->setLabels($fields->labels);
        }
        if(isset($fields->created)) {
            $issue->setCreatedAt(new \DateTime($fields->created));
        }
        if(isset($fields->priority)) {
            $issue->setPriority($this->newIssuePriority($fields->priority));
        }
        if(isset($fields->components)) {
            $components = array();
            foreach($fields->components as $component) {
                $components[] = $this->newComponent($component);
            }

            $issue->setComponents($components);
        }
        if(isset($fields->fixVersions)) {
            $versions = array();
            foreach($fields->fixVersions as $version) {
                $versions[] = $this->newVersion($version);
            }

            $issue->setVersions($versions);
        }
        if (isset($fields->resolution)) {
            $issue->setResolution($this->newResolution($fields->resolution));
            if (isset($fields->resolutiondate)) {
                $issue->setResolvedAt(new \DateTime($fields->resolutiondate));
            }
        }
        if(isset($fields->subtasks)) {
            $subTasks = array();
            foreach($fields->subtasks as $task) {
                $subTask = $this->newIssue($task);
                $subTask->setProject($issue->getProject());
                $subTasks[] = $subTask;
            }

            $issue->setSubTasks($subTasks);
        }
        if(isset($fields->assignee)) {
            $issue->setAssignee($this->newUser($fields->assignee));
        }
        if(isset($fields->reporter)) {
            $issue->setReporter($this->newUser($fields->reporter));
        }
        // Not all comments are imported to entity, need page getter from api
        if(isset($fields->comment)) {
            $comments = array();

            foreach ($fields->comment->comments as $comment) {
                $comments[] = $this->newComment($comment);
            }

            $issue->setComments($comments);
        }
        if(isset($fields->issuelinks)) {
            $links = array();
            foreach ($fields->issuelinks as $link) {
                $links[] = $this->newIssueLink($link);
            }
            $issue->setLinks($links);
        }

        return $issue;
    }

    /**
     * @return callable
     */
    private function fillIssueReferenceCloser()
    {
        $client = $this->client;
        $em = $this;
        $fillIssueReference = function(Issue $issue) use ($em, $client) {
            $data = $client->getIssueData($issue->getKey());
            $em->fillIssueWithData($issue, $data);
        };

        return $fillIssueReference;
    }

    /**
     * @param \stdClass $statusData
     *
     * @return IssueStatus
     */
    public function newIssueStatus(\stdClass $statusData)
    {
        $status = $this->getFromCache($this::ISSUE_STATUS_CACHE_KEY, $statusData) ? : new IssueStatus();
        if(empty($statusData) || $status->getId()){
            return $status;
        }

        $status->setId($statusData->id)
               ->setName($statusData->name)
               ->setDescription($statusData->description)
               ->setIconUrl($statusData->iconUrl);

        $this->addToCache($this::ISSUE_STATUS_CACHE_KEY, $status);

        return $status;
    }

    /**
     * @param \stdClass $typeData
     *
     * @return IssueType
     */
    public function newIssueType(\stdClass $typeData)
    {
        $type = $this->getFromCache($this::ISSUE_TYPE_CACHE_KEY, $typeData) ? : new IssueType();
        if(empty($typeData) || $type->getId()){
            return $type;
        }

        $type->setId($typeData->id)
             ->setName($typeData->name)
             ->setDescription($typeData->description)
             ->setSubTask($typeData->subtask)
             ->setIconUrl($typeData->iconUrl);

        $this->addToCache($this::ISSUE_TYPE_CACHE_KEY, $type);

        return $type;
    }

    /**
     * @param \stdClass $projectData
     *
     * @return Project
     */
    public function newProject(\stdClass $projectData)
    {
        $project = $this->getFromCache($this::PROJECT_CACHE_KEY, $projectData) ? : new Project();
        if(empty($projectData) || $project->getId()){
            return $project;
        }

        $project->setId($projectData->id)
                ->setKey($projectData->key)
                ->setName($projectData->name)
                ->setAvatarUrl($projectData->avatarUrls->{'48x48'});

        $this->addToCache($this::PROJECT_CACHE_KEY, $project);

        return $project;
    }

    /**
     * @param \stdClass $componentData
     *
     * @return Component
     */
    public function newComponent(\stdClass $componentData)
    {
        $component = $this->getFromCache($this::COMPONENT_CACHE_KEY, $componentData) ? : new Component();
        if(empty($componentData) || $component->getId()){
            return $component;
        }

        $component->setId($componentData->id)
                  ->setName($componentData->name);

        if (isset($componentData->description)) {
            $component->setDescription($componentData->description);
        }

        $this->addToCache($this::COMPONENT_CACHE_KEY, $component);

        return $component;
    }

    /**
     * @param \stdClass $versionData
     *
     * @return Version
     */
    public function newVersion(\stdClass $versionData)
    {
        $version = $this->getFromCache($this::VERSION_CACHE_KEY, $versionData) ? : new Version();
        if(empty($versionData) || $version->getId()){
            return $version;
        }

        $version->setId($versionData->id)
                ->setName($versionData->name)
                ->setDescription($versionData->description)
                ->setArchived($versionData->archived)
                ->setReleased($versionData->released);

        $this->addToCache($this::VERSION_CACHE_KEY, $version);

        return $version;
    }

    /**
     * @param \stdClass $userData
     *
     * @return User
     */
    public function newUser(\stdClass $userData)
    {
        $user = $this->getFromCache($this::USER_CACHE_KEY, $userData) ? : new User();
        if (empty($userData) || $user->getId()) {
            return $user;
        }

        $user->setId($userData->name)
             ->setName($userData->displayName)
             ->setEmail($userData->emailAddress)
             ->setAvatarUrl($userData->avatarUrls->{'48x48'})
             ->setActive($userData->active);

        $this->addToCache($this::USER_CACHE_KEY, $user);

        return $user;
    }

    /**
     * @param \stdClass $commentData
     *
     * @return Comment
     */
    public function newComment(\stdClass $commentData)
    {
        $comment = $this->getFromCache($this::COMMENT_CACHE_KEY, $commentData) ? : new Comment();
        if(empty($commentData) || $comment->getId()){
            return $comment;
        }

        $comment->setId($commentData->id)
                ->setBody($commentData->body)
                ->setCreatedAt($commentData->created)
                ->setUpdatedAt($commentData->updated)
                ->setAuthor($this->newUser($commentData->author));

        $this->addToCache($this::COMMENT_CACHE_KEY, $comment);

        return $comment;
    }

    /**
     * @param \stdClass $linkData
     *
     * @return IssueLink
     */
    public function newIssueLink(\stdClass $linkData)
    {
        $link = $this->getFromCache($this::ISSUE_LINK_CACHE_KEY, $linkData) ? : new IssueLink();
        if(empty($linkData) || $link->getId()){
            return $link;
        }

        $link->setId($linkData->id)
             ->setType($this->newIssueLinkType($linkData->type));

        if(isset($linkData->inwardIssue)) {
            $link->setInwardIssue($this->newIssue($linkData->inwardIssue));
        } else {
            $link->setOutwardIssue($this->newIssue($linkData->outwardIssue));
        }

        $this->addToCache($this::ISSUE_LINK_CACHE_KEY, $link);

        return $link;
    }

    /**
     * @param \stdClass $typeData
     *
     * @return IssueLinkType
     */
    public function newIssueLinkType(\stdClass $typeData)
    {
        $type = $this->getFromCache($this::ISSUE_LINK_TYPE_CACHE_KEY, $typeData) ? : new IssueLinkType();
        if(empty($typeData) || $type->getId()){
            return $type;
        }

        $type->setId($typeData->id)
             ->setName($typeData->name)
             ->setInward($typeData->inward)
             ->setOutward($typeData->inward);

        $this->addToCache($this::ISSUE_LINK_TYPE_CACHE_KEY, $type);

        return $type;
    }

    /**
     * @param \stdClass $priorityData
     *
     * @return IssuePriority
     */
    public function newIssuePriority(\stdClass $priorityData)
    {
        $priority = $this->getFromCache($this::ISSUE_PRIORITY_CACHE_KEY, $priorityData) ? : new IssuePriority();
        if(empty($priorityData) || $priority->getId()){
            return $priority;
        }

        $priority->setId($priorityData->id)
                 ->setName($priorityData->name)
                 ->setIconUrl($priorityData->iconUrl);

        $this->addToCache($this::ISSUE_PRIORITY_CACHE_KEY, $priority);

        return $priority;
    }

    /**
     * @param \stdClass $resolutionData
     *
     * @return Resolution
     */
    public function newResolution(\stdClass $resolutionData)
    {
        $resolution = $this->getFromCache($this::RESOLUTION_CACHE_KEY, $resolutionData) ? : new Resolution();
        if(empty($resolutionData) || $resolution->getId()){
            return $resolution;
        }

        $resolution->setId($resolutionData->id)
                   ->setName($resolutionData->name)
                   ->setDescription($resolutionData->description);

        $this->addToCache($this::RESOLUTION_CACHE_KEY, $resolution);

        return $resolution;
    }

    /**
     * @param string    $type
     * @param \stdClass $data
     *
     * @return bool|CommonEntity
     */
    private function getFromCache($type, \stdClass $data)
    {
        if(isset($this->cache[$type]) && (!empty($data->id) || !empty($data->name))) {
            /** @var CommonEntity $entity */
            foreach ($this->cache[$type] as $entity) {
                if($entity->getId() == (isset($data->id) ? $data->id : $data->name)) {
                    return $entity;
                }
            }
        }

        return false;
    }

    /**
     * @param string $key
     *
     * @return bool|Project
     */
    private function getProjectFromCacheByKey($key)
    {
        if(isset($this->cache[$this::PROJECT_CACHE_KEY])) {
            /** @var Project $entity */
            foreach ($this->cache[$this::PROJECT_CACHE_KEY] as $entity) {
                if($entity->getKey() == $key) {
                    return $entity;
                }
            }
        }

        return false;
    }

    /**
     * @param string       $type
     * @param CommonEntity $entity
     */
    private function addToCache($type, CommonEntity $entity)
    {
        $this->cache[$type][] = $entity;
        if(count($this->cache[$type]) > 1) {
            $this->cache[$type] = array_unique($this->cache[$type]);
        }
    }
}
