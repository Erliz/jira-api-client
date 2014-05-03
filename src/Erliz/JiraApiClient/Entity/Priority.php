<?php

namespace Erliz\JiraApiClient\Entity;

/**
 * Priority.
 *
 * @author Stanislav Vetlovskiy <s.vetlovskiy@corp.mail.ru>
 */ 
class Priority extends CommonEntity
{
    /** @var string */
    private $name;
    /** @var string */
    private $iconUrl;

    public function __construct(\stdClass $data)
    {
        $this->name = $data->name;
        $this->iconUrl = $data->iconUrl;
    }
}
