<?php

namespace Erliz\JiraApiClient\Http;

/**
 * ClientInterface.
 *
 * @author Stanislav Vetlovskiy <s.vetlovskiy@corp.mail.ru>
 */ 
interface ClientInterface
{
    /**
     * @param string $url
     *
     * @return \stdClass
     */
    public function get($url);
    public function post($url, $params);

    /**
     * @param string $name
     * @param string $value
     */
    public function addHeader($name, $value);
}
