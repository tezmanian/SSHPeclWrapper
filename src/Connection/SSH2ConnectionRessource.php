<?php

/**
 * PHPssh2 (https://github.com/tezmanian/PHP-ssh)
 *
 * @copyright Copyright (c) 2016-2019 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 */

namespace Tez\PHPssh2\Connection;

/**
 * Class SSH2ConnectionRessource
 * @package Tez\PHPssh2\Connection
 */
class SSH2ConnectionRessource implements ISSH2ConnectionRessource
{

    /**
     *
     * resource
     */
    private $_resource = null;

    /**
     * SSH2ConnectionRessource constructor.
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

}
