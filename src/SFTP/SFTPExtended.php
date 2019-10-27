<?php

/**
 * PHPssh2 (https://github.com/tezmanian/SSHPeclWrapper)
 *
 * @copyright Copyright (c) 2016-2019 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 */

namespace Tez\PHPssh2\SFTP;


use Tez\PHPssh2\Exception\SFTPException;
use Tez\PHPssh2\ISSH2;

class SFTPExtended extends SFTP implements ISFTPExtended
{

    public function __construct(ISSH2 $ssh2)
    {
        parent::__construct($ssh2);
    }


    /**
     * change group of file or directory.
     *
     * @param string $path
     * @param string $group
     * @return void
     * @throws SFTPException
     */
    public function chgrp(string $path, string $group): void
    {
        if (false === ($stream = ssh2_exec($this->_getSSH2Resource(), sprintf('chgrp %s %s', $group, $this->_getPath($path)))))
        {
            throw new SFTPException("could not execute SSH command.");
        }
        $errorStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
        stream_set_blocking($errorStream, true);
        $error = stream_get_contents($errorStream);
        if (!empty($error))
        {
            throw new SFTPException($error);
        }
    }

    /**
     * change owner of file or directory.
     *
     * @param string $path
     * @param string $owner
     * @return void
     * @throws SFTPException
     */
    public function chown(string $path, string $owner): void
    {
        if (false === ($stream = ssh2_exec($this->_getSSH2Resource(), sprintf('chown %s %s', $owner, $this->_getPath($path)))))
        {
            throw new SFTPException("could not execute SSH command.");
        }
        $errorStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
        stream_set_blocking($errorStream, true);
        $error = stream_get_contents($errorStream);
        if (!empty($error))
        {
            throw new SFTPException($error);
        }
    }

    /**
     * create a link of file or directory.
     *
     * @param string $org_path
     * @param string $new_path
     * @param string $options
     * @return void
     * @throws SFTPException
     */
    public function ln(string $org_path, string $new_path, string $options): void
    {
        $_options = "";
        if (strpos($options, "s"))
        {
            $_options = sprintf("%s -s", $_options);
        }
        if (false === ($stream = ssh2_exec($this->_getSSH2Resource(), sprintf('ln %s %s %s', $_options, $this->_getPath($org_path), $this->_getPath($new_path)))))
        {
            throw new SFTPException("could not execute SSH command.");
        }
        $errorStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
        stream_set_blocking($errorStream, true);
        $error = stream_get_contents($errorStream);
        if (!empty($error))
        {
            throw new SFTPException($error);
        }
    }


}