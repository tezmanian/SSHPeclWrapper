<?php

/**
 * PHPssh2 (https://github.com/tezmanian/SSHPeclWrapper)
 *
 * @copyright Copyright (c) 2016-2019 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 */

namespace Tez\PHPssh2\SCP;


use Tez\PHPssh2\ISSH2;
use Tez\PHPssh2\ISSH2Resource;

class SCP implements ISSH2Resource, ISCP
{
    /**
     * @var ISSH2
     */
    private $_ssh2;

    /**
     * SCP constructor.
     * @param ISSH2 $ssh2
     */
    public function __construct(ISSH2 $ssh2)
    {
        $this->setSSH2Connection($ssh2);
    }

    /**
     * @param ISSH2 $ssh2
     */
    public function setSSH2Connection(ISSH2 $ssh2): void
    {
        $this->_ssh2 = $ssh2;
    }

    /**
     * @inheritDoc
     */
    public function getSSH2Connection(): ISSH2
    {
        return $this->_ssh2;
    }

    /**
     * send a file with scp.
     *
     * @param String $local
     * @param String $remote
     * @param int $mode
     * @return boolean
     */
    public function send(string $local, string $remote, int $mode = -1): bool
    {
        if ($mode == -1)
        {
            $mode = (int)octdec(substr(sprintf('%o', fileperms($local)), -4));
        } else if (is_string($mode))
        {
            $mode = (int)octdec($mode);
        }

        return ssh2_scp_send($this->_ssh2->getConnection(), $local, $remote, $mode);
    }


    /**
     * receive a remote file with scp.
     *
     * @param String $remote
     * @param String $local
     * @return boolean
     */
    public function recv(string $remote, string $local): bool
    {
        return ssh2_scp_recv($this->_ssh2->getConnection(), $remote, $local);
    }
}