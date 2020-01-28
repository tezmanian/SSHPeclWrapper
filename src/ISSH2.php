<?php

/**
 *
 * PHPssh2 (https://github.com/tezmanian/SSHPeclWrapper)
 *
 * @copyright Copyright (c) 2016 - 2020 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 *
 */

namespace Tez\PHPssh2;

use Tez\PHPssh2\Connection\ISSH2ConnectionResource;
use Tez\PHPssh2\Credentials\ISSH2Credentials;
use Tez\PHPssh2\SCP\ISCP;
use Tez\PHPssh2\SFTP\ISFTPResource;

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
     * returns a ISSH2ConnectionResource
     * @return ISSH2ConnectionResource
     */
    public function getConnectionResource(): ISSH2ConnectionResource;

    /**
     * returns a sftp connection
     *
     * @return ISFTPResource
     */
    public function getSFTP(): ISFTPResource;

    /**
     * returns a scp connection
     *
     * @return ISCP
     */
    public function getSCP(): ISCP;
}
