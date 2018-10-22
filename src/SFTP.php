<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RootZone\SSH2;

/**
 * Description of SFTP
 *
 * @author halberstadt
 */
class SFTP implements ISFTP
{

  protected $_ssh2 = null;

  public function __construct(ISSH2 $ssh2)
  {
    $this->setSSH2Connection($ssh2);
  }

  public function setSSH2Connection(ISSH2 $ssh2)
  {
    $this->_ssh2 = $ssh2;
  }

}
