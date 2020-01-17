<?php

/**
 * PHPssh2 (https://github.com/tezmanian/SSHPeclWrapper)
 *
 * @copyright Copyright (c) 2016-2019 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 */

namespace Tez\PHPssh2\Connection;

/**
 * Interface ISSH2ConnectionResource
 * @package Tez\PHPssh2\Connection
 */
interface ISSH2ConnectionResource
{

    /**
     * Returns the SSH2Connection
     */
    public function getConnection();

    /**
     * disconnect a active session
     */
    public function disconnect(): void;
}
