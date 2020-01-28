<?php

/**
 *
 * PHPssh2 (https://github.com/tezmanian/SSHPeclWrapper)
 *
 * @copyright Copyright (c) 2016 - 2020 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 *
 */

namespace Tez\PHPssh2\Credentials;

use Tez\PHPssh2\Connection\ISSH2ConnectionResource;
use Tez\PHPssh2\Exception\SSH2AuthenticationException;

/**
 * Class SSH2AuthPrivKeyFile
 * @package Tez\PHPssh2\Auth
 */
class SSH2AuthPrivKeyFile extends SSH2AuthPrivPubKeyFile
{

    /**
     * SSH2AuthPrivKeyFile constructor.
     * @param string $username
     * @param string $keyfile
     * @param string|null $passphrase
     * @throws SSH2AuthenticationException
     */
    public function __construct(string $username, string $keyfile, string $passphrase = null)
    {
        parent::__construct($username, $keyfile, '', $passphrase);
        $this->convertPrivToPub();
    }

    /**
     * convert private key to public one
     * @throws SSH2AuthenticationException
     */
    protected function convertPrivToPub(): void
    {
        $this->createTempPubKeyName();

        $key = file_get_contents($this->getKeyFile());

        if (is_null($this->_passphrase) || empty($this->_passphrase))
        {
            $privKey = openssl_pkey_get_private($key);
        } else
        {
            $privKey = openssl_pkey_get_private($key, $this->_passphrase);
        }

        $pubKey = $this->sshEncodePublicKey($privKey);

        file_put_contents($this->getPubKeyFile(), $pubKey);
    }

    /**
     * create temp key file
     */
    private function createTempPubKeyName(): void
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'pub_');
        $this->setPubKeyFile($tempFile);
    }

    /**
     * Encode public key
     *
     * @param $privKey
     * @return string
     */
    protected function sshEncodePublicKey($privKey): string
    {
        $keyInfo = openssl_pkey_get_details($privKey);
        $buffer = pack("N", 7) . "ssh-rsa" .
            $this->sshEncodeBuffer($keyInfo['rsa']['e']) .
            $this->sshEncodeBuffer($keyInfo['rsa']['n']);
        return "ssh-rsa " . base64_encode($buffer);
    }

    /**
     * create encode buffer
     *
     * @param $buffer
     * @return false|string
     */
    protected function sshEncodeBuffer($buffer): string
    {
//    $len = mb_strlen($buffer, '8bit');
        $len = strlen($buffer);
        if (ord($buffer[0]) & 0x80)
        {
            $len++;
            $buffer = "\x00" . $buffer;
        }
        return pack("Na*", $len, $buffer);
    }

    /**
     * @throws SSH2AuthenticationException
     */
    public function __destruct()
    {
        $this->unlinkTempPubKey();
    }

    /**
     * remove temp pub key
     * @throws SSH2AuthenticationException
     */
    protected function unlinkTempPubKey(): void
    {
        unlink($this->getPubKeyFile());
    }

    /**
     * Key authentication method
     *
     * @param ISSH2ConnectionResource $connection
     * @throws SSH2AuthenticationException
     */
    public function authenticate(ISSH2ConnectionResource $connection): void
    {
        parent::authenticate($connection);
    }
}

/**
 * $rsaKey = openssl_pkey_new(array(
 * 'private_key_bits' => 1024,
 * 'private_key_type' => OPENSSL_KEYTYPE_RSA));
 *
 * $privKey = openssl_pkey_get_private($rsaKey);
 * openssl_pkey_export($privKey, $pem); //Private Key
 * $pubKey = sshEncodePublicKey($rsaKey); //Public Key
 *
 * $umask = umask(0066);
 * file_put_contents('/tmp/test.rsa', $pem); //save private key into file
 * file_put_contents('/tmp/test.rsa.pub', $pubKey); //save public key into file
 *
 * print "Private Key:\n $pem \n\n";
 * echo "Public key:\n$pubKey\n\n";
 *
 * function sshEncodePublicKey($privKey) {
 * $keyInfo = openssl_pkey_get_details($privKey);
 * $buffer  = pack("N", 7) . "ssh-rsa" .
 * sshEncodeBuffer($keyInfo['rsa']['e']) .
 * sshEncodeBuffer($keyInfo['rsa']['n']);
 * return "ssh-rsa " . base64_encode($buffer);
 * }
 *
 * function sshEncodeBuffer($buffer) {
 * $len = strlen($buffer);
 * if (ord($buffer[0]) & 0x80) {
 * $len++;
 * $buffer = "\x00" . $buffer;
 * }
 * return pack("Na*", $len, $buffer);
 * }
 */