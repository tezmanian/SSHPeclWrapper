<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RootZone\SSH2;

/**
 *
 * @author halberstadt
 */
interface ISFTP
{
  /**
   *
   * @param \RootZone\SSH2\ISSH2 $ssh2
   */
  public function setSSH2Connection(ISSH2 $ssh2);
}
