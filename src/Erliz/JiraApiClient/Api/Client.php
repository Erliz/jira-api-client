<?php

namespace Erliz\JiraApiClient\Api;

use Erliz\JiraApiClient\Entity\Issue;
use Erliz\JiraApiClient\Entity\Project;
use Erliz\JiraApiClient\Http\ClientInterface;
use Erliz\JiraApiClient\Manager\EntityManager;

/**
 * Client.
 *
 * @author Stanislav Vetlovskiy <s.vetlovskiy@corp.mail.ru>
 */ 
class Client
{
    /** @var string */
    private $protocol;
    /** @var string */
    private $host;
    /** @var string */
    private $restUri = 'rest/%s/latest';

    /** @var ClientInterface */
    private $client;
    /** @var EntityManager */
    private $em;
    /** @var string */
    private $encodeCredentials;

    /**
     * @param string $host
     * @param bool   $ssl
     */
    public function __construct($host, $ssl = true)
    {
        $this->host = $host;
        $this->protocol = $ssl ? 'https' : 'http';
    }

    /**
     * @param string $login
     * @param string $password
     */
    public function setCredentials($login, $password)
    {
        $this->encodeCredentials = base64_encode(sprintf('%s:%s', $login, $password));
        if (!empty($this->client)) {
            $this->setAuthorizationHeader();
        }
    }

    private function setAuthorizationHeader()
    {
        $this->client->addHeader('Authorization', 'Basic ' . $this->encodeCredentials);
    }

    /**
     * @param EntityManager $em
     */
    public function setEntityManager(EntityManager $em)
    {
        $em->setApiClient($this);
        $this->em = $em;
    }

    /**
     * @param string $key
     *
     * @return \stdClass
     */
    public function getIssueData($key)
    {
        return $this->client->get(
            $this->getUrl(
                sprintf(
                    'issue/%s',
                    strtoupper($key)
                )
            )
        );
    }

    /**
     * @param string $key
     *
     * @return Issue
     */
    public function getIssue($key)
    {
        return $this->em->newIssue($this->getIssueData($key));
    }

    /**
     * @param string $key
     *
     * @return Project
     */
    public function getProject($key)
    {
        $response = $this->client->get(
            $this->getUrl(
                sprintf('project/%s', strtoupper($key))
            )
        );

        return $this->em->newProject($response);
    }

    /**
     * @param string $action
     * @param array  $params
     * @param string $type
     *
     * @return string
     */
    private function getUrl($action, array $params = array(), $type = 'api')
    {
        return sprintf(
            '%s://%s/%s/%s%s',
            $this->getProtocol(),
            $this->getHost(),
            sprintf($this->getRestUri(), $type),
            $action,
            empty($params) ? '' : ('?' . http_build_query($params))
        );
    }

    /**
     * @param ClientInterface $client
     *
     * @return $this
     */
    public function setHttpClient(ClientInterface $client)
    {
        $this->client = $client;
        if (!empty($this->encodeCredentials)) {
            $this->setAuthorizationHeader();
        }

        return $this;
    }

    /**
     * @return string
     */
    private function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * @return string
     */
    private function getHost()
    {
        return $this->host;
    }

    /**
     * @return string
     */
    private function getRestUri()
    {
        return $this->restUri;
    }

    /**
     * @param string $apiUri
     */
    public function setRestUri($apiUri)
    {
        $this->restUri = $apiUri;
    }
}
