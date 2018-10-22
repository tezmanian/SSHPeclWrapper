<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RootZone\SSH2\Auth;

use RootZone\SSH2\Connection\ISSH2ConnectionRessource;

/**
 *
 * @author halberstadt
 */
interface ISSH2Credentials
{

  /**
   *
   * @param \RootZone\SSH2\Connection\ISSH2ConnectionRessource $connection
   * @throws \RootZone\SSH2\Exception\SSH2AuthenticationException
   */
  public function authenticate(ISSH2ConnectionRessource $connection);
}
