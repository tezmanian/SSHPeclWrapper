<?php

/**
 *
 * PHPssh2 (https://github.com/tezmanian/SSHPeclWrapper)
 *
 * @copyright Copyright (c) 2016 - 2020 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 *
 */

namespace Tez\PHPssh2\SCP;


interface ISCP
{

    /**
     * send a file with scp.
     *
     * @param String $local
     * @param String $remote
     * @param int $mode
     * @return boolean
     */
    public function send(string $local, string $remote, int $mode = -1): bool;

    /**
     * receive a remote file with scp.
     *
     * @param String $remote
     * @param String $local
     * @return boolean
     */
    public function recv(string $remote, string $local): bool;

}