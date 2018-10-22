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
 * Description of SSH2AuthPassword
 *
 * @author halberstadt
 */
class SSH2AuthPassword extends SSH2AuthNone
{

  /**
   *
   * @var string
   */
  private $_password;

  /**
   *
   * @param string $username
   * @param string $password
   */
  public function __construct(string $username, string $password)
  {
    parent::__construct($username);
    $this->setPassword($password);
  }

  /**
   *
   * @param ISSH2ConnectionRessource $connection
   * @throws SSH2AuthenticationException
   */
  public function authenticate(ISSH2ConnectionRessource $connection)
  {
    $auth = ssh2_auth_password($connection->getConnection(), $this->getUsername(), $this->getPassword());

    if ($auth !== true)
    {
      throw new SSH2AuthenticationException(sprintf('Authentication for user %s not successful', $this->_username));
    }
  }

  /**
   *
   * @param string $password
   */
  public function setPassword(string $password)
  {
    $this->_password = $password;
  }

  /**
   *
   * @return string
   */
  private function getPassword() : string
  {
    return $this->_password;
  }

}
