<?php

/**
 * PHPssh2 (https://github.com/tezmanian/PHP-ssh)
 *
 * @copyright Copyright (c) 2016-2019 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 */

namespace Tez\PHPssh2\SFTP;

use Tez\PHPssh2\ISSH2;

/**
 * Class SFTP
 * @package Tez\PHPssh2
 */
class SFTP implements ISFTP
{

    /**
     * @var mixed
     */
    protected $_ssh2 = null;

    /**
     * SFTP constructor.
     * @param ISSH2 $ssh2
     */
    public function __construct(ISSH2 $ssh2)
    {
        $this->setSSH2Connection($ssh2);
    }

    /**
     * @param ISSH2 $ssh2
     * @return ISFTP
     */
    public function setSSH2Connection(ISSH2 $ssh2): ISFTP
    {
        $this->_ssh2 = $ssh2;
        return $this;
    }

}
