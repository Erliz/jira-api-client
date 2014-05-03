<?php

namespace Erliz\JiraApiClient\Entity;

/**
 * IssueLinkType.
 *
 * @author Stanislav Vetlovskiy <s.vetlovskiy@corp.mail.ru>
 */ 
class IssueLinkType extends CommonEntity
{
    /** @var string */
    private $name;
    /** @var string */
    private $inward;
    /** @var string */
    private $outward;

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
    public function getInward()
    {
        return $this->inward;
    }

    /**
     * @return string
     */
    public function getOutward()
    {
        return $this->outward;
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
     * @param string $inward
     *
     * @return $this
     */
    public function setInward($inward)
    {
        $this->inward = $inward;

        return $this;
    }

    /**
     * @param string $outward
     *
     * @return $this
     */
    public function setOutward($outward)
    {
        $this->outward = $outward;

        return $this;
    }
}
