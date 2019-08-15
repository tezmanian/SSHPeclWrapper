<?php

/**
 * PHPssh2 (https://github.com/tezmanian/PHP-ssh)
 *
 * @copyright Copyright (c) 2016-2019 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 */

namespace Tez\PHPssh2\Auth;

use Tez\PHPssh2\Connection\ISSH2ConnectionRessource;
use Tez\PHPssh2\Exception\SSH2AuthenticationException;

/**
 * Class SSH2AuthNone
 * @package Tez\PHPssh2\Auth
 */
class SSH2AuthNone implements ISSH2Credentials
{

    /**
     *
     * @var string
     */
    private $_username;


    /**
     * SSH2AuthNone constructor.
     *
     * @param string $username
     */
    public function __construct(string $username)
    {
        $this->setUsername($username);
    }


    /**
     *
     * @param ISSH2ConnectionRessource $connection
     * @throws SSH2AuthenticationException
     */
    public function authenticate(ISSH2ConnectionRessource $connection): void
    {
        $auth = ssh2_auth_none($connection->getConnection(), $this->getUsername());

        if ($auth !== true)
        {
            throw new SSH2AuthenticationException(sprintf('Authentication not successful, following methods allowed: %s', implode($auth)));
        }
    }

    /**
     * Get login username
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->_username;
    }

    /**
     * Set username to login
     *
     * @param string $username
     * @return SSH2AuthNone
     */
    public function setUsername(string $username): SSH2AuthNone
    {
        $this->_username = $username;
        return $this;
    }

}
