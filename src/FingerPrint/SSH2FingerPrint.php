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


use ReflectionClass;
use ReflectionException;
use Tez\PHPssh2\Connection\ISSH2ConnectionResource;
use Tez\PHPssh2\Exception\SSH2Exception;
use Tez\PHPssh2\Exception\SSH2FingerPrintException;

class SSH2FingerPrint implements ISSH2FingerPrint
{
    /**
     * @var ISSH2ConnectionResource
     */
    protected $_ssh2ConnectionResource = null;

    private $_fingerPrint = null;

    private $_flags = ISSH2FingerPrintEncryption::SHA1 | ISSH2FingerPrintOutput::HEX;

    public function setISSH2ConnectionResource(ISSH2ConnectionResource $ssh2ConnectionResource): void
    {
        $this->_ssh2ConnectionResource = $ssh2ConnectionResource;
    }

    /**
     * @param int $encryption
     * @throws SSH2FingerPrintException
     */
    public function setEncryptionMethod(int $encryption): void
    {
        try {
            if (!in_array($encryption, $this->getConstants(ISSH2FingerPrintEncryption::class))) {
                throw new SSH2FingerPrintException('unkown encryption method');
            }
        } catch (ReflectionException $e) {
        }
        $this->_flags = $encryption;

    }

    /**
     * @param string $classname
     * @return array
     * @throws ReflectionException
     */
    private function getConstants(string $classname): array
    {
        $ref = new ReflectionClass($classname);
        return $ref->getConstants();
    }

    /**
     * returns fingerprint encryption method.
     * possible values are MD5 | SHA1
     * @throws SSH2FingerPrintException
     */
    public function getEncryptionMethod(): string
    {
        try {
            foreach ($this->getConstants(ISSH2FingerPrintEncryption::class) as $encName => $encBit) {
                if ($this->_flags == ($encBit | ISSH2FingerPrintOutput::HEX)) {
                    return $encName;
                }
            }
        } catch (ReflectionException $e) {
        }
        throw new SSH2FingerPrintException('Could not get encryption');
    }

    /**
     * @throws SSH2Exception
     */
    public function checkFingerPrint(): string
    {
        $fingerPrint = $this->getHostFingerPrint();
        if (is_null($this->_fingerPrint)) {
            return $fingerPrint;
        }

        if ($this->_fingerPrint != $fingerPrint) {
            throw new SSH2FingerPrintException('Hostkey missmatch: Possible man in middle attack');
        }
        return $fingerPrint;
    }

    private function getHostFingerPrint(): string
    {
        return ssh2_fingerprint($this->_ssh2ConnectionResource->getSession(), $this->_flags);
    }

    /**
     * @param string $fingerPrint
     */
    public function setFingerPrint(string $fingerPrint): void
    {
        $this->_fingerPrint = $fingerPrint;
    }
}