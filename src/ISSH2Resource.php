<?php

/**
 * PHPssh2 (https://github.com/tezmanian/SSHPeclWrapper)
 *
 * @copyright Copyright (c) 2016-2019 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 */

namespace Tez\PHPssh2;


/**
 * Interface ISSH2Resource
 * @package Tez\PHPssh2
 */
interface ISSH2Resource
{
    /**
     * @param ISSH2 $ssh2
     */
    public function setSSH2Connection(ISSH2 $ssh2): void;
}