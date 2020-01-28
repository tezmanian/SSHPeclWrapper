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


use Tez\PHPssh2\Exception\SFTPException;

class SFTPExtended implements ISFTPExtended
{
    private $sftp = null;

    public function __construct(ASFTP $sftp)
    {
        $this->sftp = $sftp;
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
        if (false === ($stream = ssh2_exec($this->sftp->getSSH2Connection()->getConnectionResource()->getSession(), sprintf('chgrp %s %s', $group, $this->sftp->_getPath($path)))))
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
        if (false === ($stream = ssh2_exec($this->sftp->getSSH2Connection()->getConnectionResource()->getSession(), sprintf('chown %s %s', $owner, $this->sftp->_getPath($path)))))
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
        if (false === ($stream = ssh2_exec($this->sftp->getSSH2Connection()->getConnectionResource()->getSession(), sprintf('ln %s %s %s', $_options, $this->sftp->_getPath($org_path), $this->sftp->_getPath($new_path)))))
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