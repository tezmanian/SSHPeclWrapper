<?php

/**
 * PHPssh2 (https://github.com/tezmanian/PHP-ssh)
 *
 * @copyright Copyright (c) 2016-2019 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 */

namespace Tez\PHPssh2;

use Tez\PHPssh2\Auth\ISSH2Credentials;
use Tez\PHPssh2\Connection\ISSH2ConnectionRessource;

/**
 * Interface ISSH2
 * @package Tez\PHPssh2
 */
interface ISSH2
{

    /**
     * @param ISSH2Credentials $credentials
     * @return ISSH2
     */
    public function authentication(ISSH2Credentials $credentials): ISSH2;

    /**
     * @return mixed
     */
    public function getConnection();

    /**
     * @return ISSH2ConnectionRessource
     */
    public function getConnectionResource(): ISSH2ConnectionRessource;
}
