<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RootZone\SSH2\Connection;

/**
 * Description of SSH2ConnectionRessource
 *
 * @author halberstadt
 */
class SSH2ConnectionRessource implements ISSH2ConnectionRessource
{

  /**
   *
   * @var \SSH2Ressource
   */
  private $_resource = NULL;

  /**
   *
   * @param \SSH2Ressource $resource
   */
  public function __construct(\SSH2Ressource $resource)
  {
    $this->setConnection($resource);
  }

  /**
   *
   * @return \SSH2Ressource
   */
  public function getConnection(): \SSH2Ressource
  {
    return $this->_resource;
  }

  /**
   *
   * @param \SSH2Ressource $resource
   */
  protected function setConnection(\SSH2Ressource $resource)
  {
    $this->_resource = $resource;
  }

}
