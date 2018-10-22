<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RootZone\SSH2;

use RootZone\SSH2\Auth\ISSH2Credentials;
use RootZone\SSH2\Exception\SSH2Exception;
use RootZone\SSH2\Connection\ISSH2ConnectionRessource;

/**
 *
 * @author halberstadt
 */
interface ISSH2
{

  /**
   *
   * @param ICredentials $credentials
   * @throws SSH2Exception
   */
  public function authentication(ISSH2Credentials $credentials);

  /**
   *
   */
  public function getConnection();

  /**
   * return ISSH2ConnectionRessource
   */
  public function getConnectionResource(): ISSH2ConnectionRessource;
}
