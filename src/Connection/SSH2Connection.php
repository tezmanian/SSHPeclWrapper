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
 * Class SSH2Connection
 * @package Tez\PHPssh2\Connection
 */
class SSH2Connection implements ISSH2Connection
{

    /**
     * @var string
     */
    private $_host = '';

    /**
     * @var int
     */
    private $_port = 22;

    /**
     * SSH2Connection constructor.
     *
     * @param string $host
     */
    public function __construct(string $host = null)
    {
        if (!is_null($host))
        {
            $this->setHost($host);
        }
    }

    /**
     * Server callback on disconnect
     *
     * @param int $reason
     * @param string $message
     * @param string $language
     * @throws SSH2ConnectionException
     */
    public function disconnectCallback($reason, $message, $language)
    {
        unset($language);
        throw new SSH2ConnectionException(sprintf("Server disconnected with reason code [%d] and message: %s\n", $reason, $message));
    }

    /**
     * Server callback on debug
     *
     * @param int $reason
     * @param string $message
     * @param string $language
     */
    public function debugCallback($reason, $message, $language)
    {
        unset($language);
        error_log(sprintf("Debug: code [%d] and message: %s\n", $reason, $message));
    }

    /**
     * Connect to SSH2 server
     * @return ISSH2ConnectionResource
     * @throws SSH2ConnectionException
     */
    public function connect(): ISSH2ConnectionResource
    {

        if (false === ($connection = ssh2_connect($this->getHost(), $this->getPort(), ISSH2Connection::METHODS, $this->getCallbacks())))
        {
            throw new SSH2ConnectionException(sprintf("could not establish ssh connection to server %s at port %d", $this->getHost(), $this->getPort()));
        }

        return new SSH2ConnectionResource($connection, $this->getHost());
    }

    /**
     * returns the host
     *
     * @return string
     * @throws SSH2ConnectionException
     */
    public function getHost(): string
    {
        if (empty($this->_host)) throw new SSH2ConnectionException("Host is not set");

        return $this->_host;
    }

    /**
     * set the host
     *
     * @param string $host
     * @return ISSH2Connection
     */
    public function setHost(string $host): ISSH2Connection
    {
        $this->_host = $host;
        return $this;
    }

    /**
     * returns the port
     *
     * @return int
     */
    public function getPort(): int
    {
        return $this->_port;
    }

    /**
     *
     * @param int $port
     * @return ISSH2Connection
     */
    public function setPort(int $port): ISSH2Connection
    {
        $this->_port = $port;
        return $this;
    }

    /**
     * Callbacks
     *
     * @return array
     */
    private function getCallbacks()
    {
        return
            [
                'disconnect' => [$this, 'disconnectCallback'],
                'ignore' => [$this, 'debugCallback'],
                'macerror' => [$this, 'debugCallback'],
                'debug' => [$this, 'debugCallback'],
            ];
    }

}
