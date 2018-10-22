<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RootZone\SSH2\Auth;

use RootZone\SSH2\Connection\ISSH2ConnectionRessource;

/**
 * Description of SSH2AuthPubKeyFile
 *
 * @author halberstadt
 */
class SSH2AuthPrivPubKeyFile extends SSH2AuthNone
{

  /**
   *
   * @var string
   */
  protected $_passphrase = NULL;

  /**
   *
   * @var string
   */
  private $_keyfile = NULL;

  /**
   *
   * @var string
   */
  private $_pubkeyfile = NULL;

  /**
   *
   * @param string $username
   * @param string $keyfile
   * @param string $pubkeyfile
   * @param string $passphrase
   */
  public function __construct(string $username, string $keyfile, string $pubkeyfile, string $passphrase = NULL)
  {
    parent::__construct($username);
    $this->setKeyfile($keyfile);
    $this->setPubKeyFile($pubkeyfile);
    $this->setPassphrase($passphrase);
  }

  /**
   *
   * @param ISSH2ConnectionRessource $connection
   * @throws SSH2AuthenticationException
   */
  public function authenticate(ISSH2ConnectionRessource $connection)
  {
    if (is_null($this->_passphrase) || empty($this->_passphrase))
    {
      $auth = ssh2_auth_pubkey_file($connection->getConnection(), $this->getUsername(), $this->getPubKeyFile(), $this->getKeyFile());
    } else
    {
      $auth = ssh2_auth_pubkey_file($connection->getConnection(), $this->getUsername(), $this->getPubKeyFile(), $this->getKeyFile(), $this->_passphrase);
    }

    if ($auth !== true)
    {
      throw new SSH2AuthenticationException(sprintf('Authentication for user %s not successful', $this->_username));
    }
  }

  /**
   *
   * @param string $keyfile
   */
  public function setKeyFile(string $keyfile)
  {
    $this->_keyfile = $keyfile;
  }

  /**
   *
   * @param string $keyfile
   */
  public function setPubKeyFile(string $keyfile = NULL)
  {
    $this->_pubkeyfile = $keyfile;
  }

  /**
   *
   * @param string $passphrase
   */
  public function setPassphrase(string $passphrase = NULL)
  {
    $this->_passphrase = $passphrase;
  }

  /**
   *
   * @return string
   */
  protected function getPubKeyFile() : string
  {
    return $this->_pubkeyfile;
  }

  /**
   *
   * @return string
   */
  protected function getKeyFile() : string
  {
    return $this->_keyfile;
  }

}
