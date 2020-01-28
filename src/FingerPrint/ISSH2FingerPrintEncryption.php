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


interface ISSH2FingerPrintEncryption
{
    const MD5 = SSH2_FINGERPRINT_MD5;
    const SHA1 = SSH2_FINGERPRINT_SHA1;
}