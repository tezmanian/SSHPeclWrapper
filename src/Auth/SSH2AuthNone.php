<?php

/**
 * PHPssh2 (https://github.com/tezmanian/SSHPeclWrapper)
 *
 * @copyright Copyright (c) 2016-2019 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 */

namespace Tez\PHPssh2\Auth;

use Tez\PHPssh2\Connection\ISSH2ConnectionResource;
use Tez\PHPssh2\Exception\SSH2AuthenticationException;
use Tez\PHPssh2\Exception\SSH2AuthenticationUsernameException;

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
    private $_username = null;


    /**
     * SSH2AuthNone constructor.
     *
     * @param string $username
     */
    public function __construct(string $username = null)
    {
        if (!is_null($username))
        {
            $this->setUsername($username);
        }

    }


    /**
     *
     * @param ISSH2ConnectionResource $connection
     * @throws SSH2AuthenticationException
     */
    public function authenticate(ISSH2ConnectionResource $connection): void
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
     * @throws SSH2AuthenticationException
     */
    public function getUsername(): string
    {
        if (empty($this->_username) || is_null($this->_username))
        {
            throw new SSH2AuthenticationUsernameException('Missing username');
        }
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
