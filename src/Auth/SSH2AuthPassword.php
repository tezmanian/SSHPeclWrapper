<?php

/**
 * PHPssh2 (https://github.com/tezmanian/PHP-ssh)
 *
 * @copyright Copyright (c) 2016-2019 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 */

namespace Tez\PHPssh2\Auth;

use Tez\PHPssh2\Connection\ISSH2ConnectionResource;
use Tez\PHPssh2\Exception\SSH2AuthenticationException;

/**
 * Class SSH2AuthPassword
 * @package Tez\PHPssh2\Auth
 */
class SSH2AuthPassword extends SSH2AuthNone
{

    /**
     *
     * @var string
     */
    private $_password = null;

    /**
     * SSH2AuthPassword constructor.
     *
     * @param string $username
     * @param string $password
     */
    public function __construct(string $username = null, string $password = null)
    {
        parent::__construct($username);
        $this->setPassword($password);
    }

    /**
     * Set authentication password
     *
     * @param string $password
     * @return SSH2AuthPassword
     */
    public function setPassword(string $password = null): SSH2AuthPassword
    {
        $this->_password = $password;
        return $this;
    }

    /**
     * Password authentication method
     * @param ISSH2ConnectionResource $connection
     * @throws SSH2AuthenticationException
     */
    public function authenticate(ISSH2ConnectionResource $connection): void
    {
        $auth = ssh2_auth_password($connection->getConnection(), $this->getUsername(), $this->getPassword());

        if ($auth !== true)
        {
            throw new SSH2AuthenticationException(sprintf('Authentication for user %s not successful', $this->getUsername()));
        }
    }

    /**
     * Get password
     *
     * @return string
     * @throws SSH2AuthenticationException
     */
    private function getPassword(): string
    {
        if (empty($this->_password) || is_null($this->_password))
        {
            throw new SSH2AuthenticationException('Missing password');
        }
        return $this->_password;
    }

}
