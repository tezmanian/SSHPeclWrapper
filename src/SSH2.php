<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RootZone\SSH2;

use RootZone\SSH2\Auth\ISSH2Credentials;
use RootZone\SSH2\Connection\ISSH2Connection;
use RootZone\SSH2\Connection\ISSH2ConnectionRessource;

/**
 * Description of SSH
 *
 * @author halberstadt
 */
class SSH2 implements ISSH2
{

  /**
   *
   * @var ISSH2ConnectionRessource
   */
  private $_connectionResource = NULL;

  public function __construct(ISSH2Connection $connection, ISSH2Credentials $credentials)
  {
    $this->_checkSSH2Extension();
    $this->_connectionResource = $connection->connect();
    $this->authentication($credentials);
  }

  public function authentication(ISSH2Credentials $credentials)
  {
    $credentials->authenticate($this->_connection);
  }

  private function _checkSSH2Extension()
  {
    if (FALSE === extension_loaded("ssh2"))
    {
      throw new SSH2Exception("SSH2 extension is not loaded");
    }
  }

  /**
   *
   * @return \Resource
   */
  public function getConnection()
  {
    return $this->_connectionResource->getConnection();
  }

  /**
   *
   * @return ISSH2ConnectionRessource
   */
  public function getConnectionResource()
  {
    return $this->_connectionResource;
  }

}
