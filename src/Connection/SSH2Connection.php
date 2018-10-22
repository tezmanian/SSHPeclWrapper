<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RootZone\SSH2\Connection;

use RootZone\SSH2\Exception\SSH2ConnectionException;

/**
 * Description of SSH2Connection
 *
 * @author halberstadt
 */
class SSH2Connection implements ISSH2Connection
{

  private $_host = NULL;
  private $_port = 22;

  /**
   *
   * @param string $host
   */
  public function __construct(string $host)
  {
    $this->setHost($host);
  }

  /**
   *
   * @return \RootZone\SSH2\Connection\ISSH2ConnectionRessource
   * @throws \RootZone\SSH2\Exception\SSH2ConnectionException
   */
  public function connect(): ISSH2ConnectionRessource
  {



    if (FALSE === ($connection = ssh2_connect($this->getHost(), $this->getPort(), ISSH2Connection::METHODS, $this->getCallbacks())))
    {
      throw new SSH2ConnectionException(sprintf("could not establish ssh connection to server %s at port %d", $host, $port));
    }

    return new SSH2ConnectionRessource($connection);
  }

  /**
   *
   * @param int $reason
   * @param string $message
   * @param string $language
   * @throws \RootZone\SSH2\Exception\SSH2ConnectionException
   */
  public static function disconnectCallback($reason, $message, $language)
  {
    throw SSH2ConnectionException(sprintf("Server disconnected with reason code [%d] and message: %s\n", $reason, $message));
  }

  private function getCallbacks()
  {
    return
            [
                'disconnect' => \RootZone\SSH2\SSH2Connection::disconnectCallback(),
    ];
  }

  /**
   *
   * @return string
   */
  public function getHost(): string
  {
    return $this->_host;
  }

  /**
   *
   * @return int
   */
  public function getPort(): int
  {
    return $this->_port;
  }

  /**
   *
   * @param string $host
   */
  public function setHost(string $host)
  {
    $this->_host = $host;
  }

  /**
   *
   * @param int $port
   */
  public function setPort(int $port)
  {
    $this->_port = $port;
  }

}
