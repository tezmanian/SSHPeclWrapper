<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RootZone\SSH2\Auth;

use RootZone\SSH2\Connection\ISSH2ConnectionRessource;
use RootZone\SSH2\Exception\SSH2AuthenticationException;

/**
 * Description of SSH2AuthNone
 *
 * @author halberstadt
 */
class SSH2AuthNone implements ISSH2Credentials
{

  /**
   *
   * @var string
   */
  private $_username;

  /**
   *
   * @param string $username
   */
  public function __construct(string $username)
  {
    $this->setUsername($username);
  }


  /**
   *
   * @param ISSH2ConnectionRessource $connection
   * @throws SSH2AuthenticationException
   */
  public function authenticate(ISSH2ConnectionRessource $connection)
  {
    $auth = ssh2_auth_none ($connection->getConnection(), $this->getUsername());

    if ($auth !== true)
    {
      throw new SSH2AuthenticationException(sprintf('Authentication not successful, following methods allowed: %s', implode($auth)));
    }
  }

  /**
   *
   * @param string $username
   */
  public function setUsername(string $username)
  {
    $this->_username = $username;
  }

  /**
   *
   * @return string
   */
  public function getUsername() : string
  {
    return $this->_username;
  }

}
