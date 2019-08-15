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
 * Interface ISSH2Credentials
 * @package Tez\PHPssh2\Auth
 */
interface ISSH2Credentials
{

    /**
     * authentication method
     *
     * @param ISSH2ConnectionRessource $connection
     * @throws SSH2AuthenticationException
     */
    public function authenticate(ISSH2ConnectionRessource $connection): void;
}
