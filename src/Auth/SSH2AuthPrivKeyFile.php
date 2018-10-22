<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RootZone\SSH2\Auth;

use RootZone\SSH2\Connection\ISSH2ConnectionRessource;

/**
 * Description of SSH2AuthPrivKeyFile
 *
 * @author halberstadt
 */
class SSH2AuthPrivKeyFile extends SSH2AuthPrivPubKeyFile
{

  /**
   *
   * @param string $username
   * @param string $keyfile
   * @param string $passphrase
   */
  public function __construct(string $username, string $keyfile, string $passphrase = NULL)
  {
    parent::__construct($username, $keyfile, '', $passphrase);
    $this->convertPrivToPub();
  }

  public function __destruct()
  {
    $this->unlinkTempPubKey();
  }

  /**
   *
   * @param ISSH2ConnectionRessource $connection
   * @throws SSH2AuthenticationException
   */
  public function authenticate(ISSH2ConnectionRessource $connection)
  {
    parent::authenticate($connection);
  }

  private function createTempPubKeyName()
  {
    $tempFile = tempnam(sys_get_temp_dir(), $this->getKeyFile());
    $this->setPubKeyFile($tempFile);
  }

  protected function convertPrivToPub()
  {
    $this->createTempPubKeyName();

    $key = file_get_contents($this->getKeyFile());

    if (is_null($this->_passphrase) || empty($this->_passphrase))
    {
      $privKey = openssl_pkey_get_private($key);
    } else
    {
      $privKey = openssl_pkey_get_private($key,$this->_passphrase);
    }

    $pubKey = $this->sshEncodePublicKey($privKey);

    file_put_contents($this->getPubKeyFile(), $pubKey);
  }

  protected function unlinkTempPubKey()
  {
    unlink($this->getPubKeyFile());
  }

  protected function sshEncodePublicKey($privKey) {
    $keyInfo = openssl_pkey_get_details($privKey);
    $buffer  = pack("N", 7) . "ssh-rsa" .
    $this->sshEncodeBuffer($keyInfo['rsa']['e']) .
    $this->sshEncodeBuffer($keyInfo['rsa']['n']);
    return "ssh-rsa " . base64_encode($buffer);
  }

  protected function sshEncodeBuffer($buffer) {
//    $len = mb_strlen($buffer, '8bit');
    $len = strlen($buffer);
    if (ord($buffer[0]) & 0x80) {
        $len++;
        $buffer = "\x00" . $buffer;
    }
    return pack("Na*", $len, $buffer);
  }
}

  /**
$rsaKey = openssl_pkey_new(array(
              'private_key_bits' => 1024,
              'private_key_type' => OPENSSL_KEYTYPE_RSA));

$privKey = openssl_pkey_get_private($rsaKey);
openssl_pkey_export($privKey, $pem); //Private Key
$pubKey = sshEncodePublicKey($rsaKey); //Public Key

$umask = umask(0066);
file_put_contents('/tmp/test.rsa', $pem); //save private key into file
file_put_contents('/tmp/test.rsa.pub', $pubKey); //save public key into file

print "Private Key:\n $pem \n\n";
echo "Public key:\n$pubKey\n\n";

function sshEncodePublicKey($privKey) {
    $keyInfo = openssl_pkey_get_details($privKey);
    $buffer  = pack("N", 7) . "ssh-rsa" .
    sshEncodeBuffer($keyInfo['rsa']['e']) .
    sshEncodeBuffer($keyInfo['rsa']['n']);
    return "ssh-rsa " . base64_encode($buffer);
}

function sshEncodeBuffer($buffer) {
    $len = strlen($buffer);
    if (ord($buffer[0]) & 0x80) {
        $len++;
        $buffer = "\x00" . $buffer;
    }
    return pack("Na*", $len, $buffer);
}
   */