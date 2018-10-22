<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RootZone\SSH2\Connection;

/**
 *
 * @author halberstadt
 */
interface ISSH2Connection
{

  /**
   *
   */
  const METHODS = [
              'kex' => 'diffie-hellman-group1-sha1,diffie-hellman-group14-sha1,diffie-hellman-group-exchange-sha1,diffie-hellman-group-exchange-sha256',
              'client_to_server' =>
              [
                  'crypt' => 'aes256-ctr,aes192-ctr,aes128-ctr,aes256-cbc,aes192-cbc,aes128-cbc,3des-cbc,blowfish-cbc,cast128-cbc,arcfour,arcfour128,none',
                  'comp' => 'zlib,none'
              ],
              'server_to_client' =>
              [
                  'crypt' => 'aes256-ctr,aes192-ctr,aes128-ctr,aes256-cbc,aes192-cbc,aes128-cbc,3des-cbc,blowfish-cbc,cast128-cbc,arcfour,arcfour128,none',
                  'comp' => 'zlib,none'
              ]
  ];

  /**
   *
   * @param string $host
   */
  public function setHost(string $host);

  /**
   *
   * @param int $port
   */
  public function setPort(int $port);

  /**
   * return SSH2ConnectionRessource
   * @throws SSH2ConnectionException
   */
  public function connect() : ISSH2ConnectionRessource;
}
