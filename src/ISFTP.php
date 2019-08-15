<?php

/**
 * PHPssh2 (https://github.com/tezmanian/PHP-ssh)
 *
 * @copyright Copyright (c) 2016-2019 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 */

namespace Tez\PHPssh2;

/**
 * Interface ISFTP
 * @package Tez\PHPssh2
 */
interface ISFTP
{

    /**
     * @param ISSH2 $ssh2
     * @return ISFTP
     */
    public function setSSH2Connection(ISSH2 $ssh2): ISFTP;
}
