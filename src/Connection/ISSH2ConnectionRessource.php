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
interface ISSH2ConnectionRessource
{

  /**
   *
   */
  public function getConnection(): SSH2Ressource;
}
