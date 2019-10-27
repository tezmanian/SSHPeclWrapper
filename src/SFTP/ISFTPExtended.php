<?php

/**
 * PHPssh2 (https://github.com/tezmanian/SSHPeclWrapper)
 *
 * @copyright Copyright (c) 2016-2019 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 */

namespace Tez\PHPssh2\SFTP;


use Tez\PHPssh2\Exception\SFTPException;

interface ISFTPExtended extends ISFTP
{

    /**
     * change group of file or directory.
     *
     * @param string $path
     * @param string $group
     * @return void
     * @throws SFTPException
     */
    public function chgrp(string $path, string $group): void;

    /**
     * change owner of file or directory.
     *
     * @param string $path
     * @param string $owner
     * @return void
     * @throws SFTPException
     */
    public function chown(string $path, string $owner): void;

    /**
     * create a link of file or directory.
     *
     * @param string $org_path
     * @param string $new_path
     * @param string $options
     * @return void
     * @throws SFTPException
     */
    public function ln(string $org_path, string $new_path, string $options): void;
}