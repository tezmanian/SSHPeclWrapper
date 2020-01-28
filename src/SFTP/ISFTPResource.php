<?php

/**
 *
 * PHPssh2 (https://github.com/tezmanian/SSHPeclWrapper)
 *
 * @copyright Copyright (c) 2016 - 2020 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 *
 */

namespace Tez\PHPssh2\SFTP;


use Tez\PHPssh2\ISSH2Resource;

interface ISFTPResource extends ISSH2Resource
{
    /**
     * connect to SFTP service
     * @return ISFTP
     */
    public function connect(): ISFTP;

    /**
     * disconnect SFTP connection, not SSH2 connection
     */
    public function quit(): void;
}