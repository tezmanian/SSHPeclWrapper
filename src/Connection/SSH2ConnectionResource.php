<?php

/**
 * PHPssh2 (https://github.com/tezmanian/SSHPeclWrapper)
 *
 * @copyright Copyright (c) 2016-2019 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 */

namespace Tez\PHPssh2\Connection;

/**
 * Class SSH2ConnectionResource
 * @package Tez\PHPssh2\Connection
 */
class SSH2ConnectionResource implements ISSH2ConnectionResource
{

    /**
     *
     * resource
     */
    private $_resource = null;

    /**
     * SSH2ConnectionResource constructor.
     * @param $resource
     */
    public function __construct($resource)
    {
        $this->setConnection($resource);
    }

    /**
     * set connection
     *
     * @param $resource
     */
    protected function setConnection($resource)
    {
        $this->_resource = $resource;
    }

    /**
     * get SSH2Ressource
     *
     * @return null
     */
    public function getConnection()
    {
        return $this->_resource;
    }

    /**
     * disconnect connection
     */
    public function disconnect(): void
    {
        if (false === is_null($this->_resource))
        {
            ssh2_disconnect($this->_resource);
        }
    }

}
