<?php

/**
 *
 * PHPssh2 (https://github.com/tezmanian/SSHPeclWrapper)
 *
 * @copyright Copyright (c) 2016 - 2020 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 *
 */

namespace Tez\PHPssh2\Connection;

use Tez\PHPssh2\Exception\SSH2ConnectionException;

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
     * @var string
     */
    private $_host = null;

    /**
     * SSH2ConnectionResource constructor.
     * @param $resource
     * @param $host
     * @throws SSH2ConnectionException
     */
    public function __construct($resource, $host)
    {
        $this->setConnection($resource);
        $this->setHost($host);
    }

    /**
     * set connection
     *
     * @param $resource
     * @throws SSH2ConnectionException
     */
    private function setConnection($resource): void
    {
        if (!$this->checkResource($resource)) {
            throw new SSH2ConnectionException('no connection available');
        }
        $this->_resource = $resource;
    }

    private function checkResource($resource): bool
    {
        return (!is_resource($resource) || 'ssh2 session' !== strtolower(get_resource_type($resource))) ? false : true;
    }

    private function setHost(string $host): void
    {
        $this->_host = $host;
    }

    /**
     * disconnect connection
     */
    public function disconnect(): void
    {
        try {
            ssh2_disconnect($this->getSession());
        } catch (SSH2ConnectionException $ex) {
            // do nothing only delete every thing
        }
        $this->_host = null;
        $this->_resource = null;
    }

    /**
     * get SSH2Resource
     *
     * @return null
     * @throws SSH2ConnectionException
     */
    public function getSession()
    {
        if (!$this->checkResource($this->_resource)) {
            throw new SSH2ConnectionException('connection broken');
        }
        return $this->_resource;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->_host;
    }

}
