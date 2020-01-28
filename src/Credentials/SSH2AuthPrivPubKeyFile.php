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
 * Class SSH2AuthPrivPubKeyFile
 * @package Tez\PHPssh2\Auth
 */
class SSH2AuthPrivPubKeyFile extends SSH2AuthNone
{

    /**
     *
     * @var string|null
     */
    protected $_passphrase = null;

    /**
     *
     * @var string|null
     */
    private $_keyfile = null;

    /**
     *
     * @var string|null
     */
    private $_pubkeyfile = null;

    /**
     * SSH2AuthPrivPubKeyFile constructor.
     *
     * @param string $username
     * @param string $keyfile
     * @param string $pubkeyfile
     * @param string|null $passphrase
     */
    public function __construct(string $username = null, string $keyfile = null, string $pubkeyfile = null, string $passphrase = null)
    {
        parent::__construct($username);
        $this->setKeyfile($keyfile);
        $this->setPubKeyFile($pubkeyfile);
        $this->setPassphrase($passphrase);
    }

    /**
     * set passphrase
     *
     * @param string $passphrase
     */
    public function setPassphrase(string $passphrase = null): void
    {
        $this->_passphrase = $passphrase;
    }

    /**
     * authentication method
     *
     * @param ISSH2ConnectionResource $connection
     * @throws SSH2AuthenticationException
     */
    public function authenticate(ISSH2ConnectionResource $connection): void
    {
        if (is_null($this->_passphrase) || empty($this->_passphrase))
        {
            $auth = ssh2_auth_pubkey_file($connection->getSession(), $this->getUsername(), $this->getPubKeyFile(), $this->getKeyFile());
        } else
        {
            $auth = ssh2_auth_pubkey_file($connection->getSession(), $this->getUsername(), $this->getPubKeyFile(), $this->getKeyFile(), $this->_passphrase);
        }

        if ($auth !== true)
        {
            throw new SSH2AuthenticationException(sprintf('Authentication for user %s not successful', $this->getUsername()));
        }
    }

    /**
     * get public key file
     *
     * @return string
     * @throws SSH2AuthenticationException
     */
    protected function getPubKeyFile(): string
    {
        if (empty($this->_pubkeyfile) || is_null($this->_pubkeyfile))
        {
            throw new SSH2AuthenticationException('Missing public key path');
        }

        if (!file_exists($this->_pubkeyfile)) {
            throw new SSH2AuthenticationException('Public key files does not exist.');
        }
        return $this->_pubkeyfile;
    }

    /**
     * set public key file
     *
     * @param string $keyfile
     * @return SSH2AuthPrivPubKeyFile
     */
    public function setPubKeyFile(string $keyfile = null): SSH2AuthPrivPubKeyFile
    {
        $this->_pubkeyfile = $keyfile;
        return $this;
    }

    /**
     * get private key file
     *
     * @return string
     * @throws SSH2AuthenticationException
     */
    protected function getKeyFile(): string
    {
        if (empty($this->_keyfile) || is_null($this->_keyfile))
        {
            throw new SSH2AuthenticationException('Missing private key path');
        }

        if (!file_exists($this->_keyfile)) {
            throw new SSH2AuthenticationException('Key file does not exist.');
        }
        return $this->_keyfile;
    }

    /**
     * set private key file
     * @param string $keyfile
     * @return SSH2AuthPrivPubKeyFile
     */
    public function setKeyFile(string $keyfile = null): SSH2AuthPrivPubKeyFile
    {
        $this->_keyfile = $keyfile;
        return $this;
    }

}
