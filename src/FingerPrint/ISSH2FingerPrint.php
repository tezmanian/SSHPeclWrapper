<?php

/**
 * PHPssh2 (https://github.com/tezmanian/SSHPeclWrapper)
 *
 * @copyright Copyright (c) 2016 - 2020 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 *
 *
 */

namespace Tez\PHPssh2\FingerPrint;


use Tez\PHPssh2\Connection\ISSH2ConnectionResource;
use Tez\PHPssh2\Exception\SSH2Exception;

interface ISSH2FingerPrint
{

    public function setISSH2ConnectionResource(ISSH2ConnectionResource $ssh2Resource): void;

    /**
     * returns fingerprint encryption method.
     * possible values are MD5 | SHA1
     * @return string
     * @throws SSH2Exception
     */
    public function getEncryptionMethod(): string;

    /**
     * @param int $encryption
     */
    public function setEncryptionMethod(int $encryption): void;

    /**
     * check host fingerprint and returns is
     * @return string
     * @throws SSH2Exception
     */
    public function checkFingerPrint(): string;

    /**
     * @param string $fingerPrint
     */
    public function setFingerPrint(string $fingerPrint): void;

}

