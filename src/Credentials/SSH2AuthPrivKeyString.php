<?php
/**
 * PHPssh2 (https://github.com/tezmanian/SSHPeclWrapper)
 *
 * @copyright Copyright (c) 2016 - 2020 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 *
 *
 */

namespace Tez\PHPssh2\Credentials;


class SSH2AuthPrivKeyString extends SSH2AuthPrivKeyFile
{

    public function __construct(string $username, string $keyString, string $passphrase = null)
    {
        $keyfile = $this->generateTempPrivKeyFile($keyString);
        parent::__construct($username, $keyfile, $passphrase);
    }

    private function generateTempPrivKeyFile(string $keyString): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'priv_');
        file_put_contents($tempFile, $keyString);
        return $tempFile;
    }

    public function __destruct()
    {
        parent::__destruct();
        unlink($this->getKeyFile());
    }
}