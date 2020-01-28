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


use Tez\PHPssh2\Exception\SSH2Exception;
use Tez\PHPssh2\Exception\SSH2FingerPrintException;

class SSH2FileBasedSSH2FingerPrint extends SSH2FingerPrint
{
    CONST DR = DIRECTORY_SEPARATOR;

    public static $fileName = 'php_ssh_fingerprint';

    public static $hostCryptMethod = 'SHA1';

    public static $location = null;

    private $_saved_fingerprint = [];

    /**
     * SSH2FileBasedSSH2FingerPrint constructor.
     * @throws SSH2FingerPrintException
     */
    public function __construct()
    {
        $this->createHostFingerPrintList();
    }

    /**
     * @throws SSH2FingerPrintException
     */
    private function createHostFingerPrintList()
    {
        foreach (explode("\n", file_get_contents($this->getLocation())) as $_host_fingerprint) {
            $_host_fingerprint_arr = explode(" ", $_host_fingerprint);
            if (is_array($_host_fingerprint_arr) && count($_host_fingerprint_arr) > 1) {
                $this->_saved_fingerprint[$_host_fingerprint_arr[0]] = $_host_fingerprint_arr[1];
            }
        }
    }

    /**
     * @return string|null
     * @throws SSH2FingerPrintException
     */
    private function getLocation()
    {
        if (is_null(self::$location) || empty(self::$location)) {
            self::$location = sprintf('%1$s%2$s.ssh%2$s%3$s', $_SERVER['HOME'], self::DR, self::$fileName);
        }

        if (!is_dir(self::$location)) {
            self::$fileName = basename(self::$location);
            self::$location = dirname(self::$location);
        }

        self::$location = rtrim(self::$location, self::DR);

        if (!is_writable(self::$location)) {
            throw new SSH2FingerPrintException(sprintf("directory '%s' is not writeable or does not exist", self::$location));
        }

        $file = sprintf('%s%s%s', rtrim(self::$location, self::DR), self::DR, self::$fileName);

        if (!file_exists($file)) {
            touch($file);
        } else if (!is_writable($file)) {
            throw new SSH2FingerPrintException(sprintf("%s is not writeable", $file));
        }
        return $file;
    }

    /**
     * @return string
     * @throws SSH2FingerPrintException
     * @throws SSH2Exception
     */
    public function checkFingerPrint(): string
    {
        $exist = $this->getFingerPrintByHost($this->_ssh2ConnectionResource->getHost());
        $fingerPrint = parent::checkFingerPrint();
        if (!$exist) {
            $this->setFingerPrintByHost($this->_ssh2ConnectionResource->getHost(), $fingerPrint);
        }
        return $fingerPrint;
    }

    /**
     * @param $host
     * @return bool
     */
    private function getFingerPrintByHost($host)
    {
        $_enc_host = $this->encryptHostName($host);
        if (key_exists($_enc_host, $this->_saved_fingerprint)) {
            $this->setFingerPrint($this->_saved_fingerprint[$_enc_host]);
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $host
     * @return string
     */
    private function encryptHostName($host): string
    {
        switch (self::$hostCryptMethod) {
            case 'SHA1':
                $host = sha1($host);
                break;
            case 'MD5':
            default:
                $host = md5($host);
        }
        return $host;
    }

    /**
     * @param $host
     * @param $fingerprint
     * @throws SSH2FingerPrintException
     */
    private function setFingerPrintByHost($host, $fingerprint): void
    {
        file_put_contents(self::getLocation(), sprintf("%s %s %s\n", $this->encryptHostName($host), $fingerprint, $host), FILE_APPEND | LOCK_EX);

    }
}