<?php

/**
 * PHPssh2 (https://github.com/tezmanian/SSHPeclWrapper)
 *
 * @copyright Copyright (c) 2016-2019 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 */

namespace Tez\PHPssh2;

use Tez\PHPssh2\Auth\ISSH2Credentials;
use Tez\PHPssh2\Connection\ISSH2Connection;
use Tez\PHPssh2\Connection\ISSH2ConnectionResource;
use Tez\PHPssh2\Exception\SSH2Exception;
use Tez\PHPssh2\SCP\ISCP;
use Tez\PHPssh2\SCP\SCP;
use Tez\PHPssh2\SFTP\ISFTP;
use Tez\PHPssh2\SFTP\ISFTPExtended;
use Tez\PHPssh2\SFTP\SFTP;
use Tez\PHPssh2\SFTP\SFTPExtended;

/**
 * Class SSH2
 * @package Tez\PHPssh2
 */
class SSH2 implements ISSH2
{

    /**
     *
     * @var ISSH2ConnectionResource|NULL
     */
    private $_connectionResource = null;

    /**
     * SSH2 constructor.
     *
     * @param ISSH2Connection $connection
     * @param ISSH2Credentials $credentials
     * @throws Exception\SSH2AuthenticationException
     * @throws SSH2Exception
     */
    public function __construct(ISSH2Connection $connection, ISSH2Credentials $credentials)
    {
        $this->_checkSSH2Extension();
        $this->_connectionResource = $connection->connect();
        $this->authentication($credentials);
    }

    /**
     * check if ssh2 extension is loaded
     *
     * @throws SSH2Exception
     */
    private function _checkSSH2Extension(): void
    {
        if (false === extension_loaded("ssh2"))
        {
            throw new SSH2Exception("SSH2 extension is not loaded");
        }
    }

    /**
     * authenticate the session
     *
     * @param ISSH2Credentials $credentials
     * @return ISSH2
     * @throws Exception\SSH2AuthenticationException
     */
    public function authentication(ISSH2Credentials $credentials): ISSH2
    {
        $credentials->authenticate($this->getConnectionResource());
        return $this;
    }

    /**
     * returns a connection resource
     *
     * @return ISSH2ConnectionResource
     */
    public function getConnectionResource(): ISSH2ConnectionResource
    {
        return $this->_connectionResource;
    }

    public function disconnect(): void
    {
        $this->getConnectionResource()->disconnect();
    }

    /**
     * returns the ssh2 connection
     *
     * @return resource
     */
    public function getConnection()
    {
        return $this->_connectionResource->getConnection();
    }

    /**
     * returns a SFTP connection
     * @return ISFTP
     */
    public function getSFTP(): ISFTP
    {
        return new SFTP($this->getSSH2());
    }

    private function getSSH2(): ISSH2
    {
        return $this;
    }

    public function getSFTPExtended(): ISFTPExtended
    {
        return new SFTPExtended($this->getSSH2());
    }

    public function getSCP(): ISCP
    {
        return new SCP($this->getSSH2());
    }

}
