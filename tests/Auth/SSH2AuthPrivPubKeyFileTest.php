<?php

/**
 * PHPssh2 (https://github.com/tezmanian/SSHPeclWrapper)
 *
 * @copyright Copyright (c) 2016-2019 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 */

namespace Tez\PHPssh2\Auth
{
    function ssh2_auth_pubkey_file($session, string $username, string $pubkeyfile, string $privkeyfile, string $passphrase = ''): bool
    {
        if (is_array($session)
            && is_string($username)
            && is_string($pubkeyfile)
            && is_string($privkeyfile)
            && (empty($passphrase) || (is_string($passphrase) && $passphrase == 'password')))
        {
            return true;
        }
        return false;
    }
}


namespace TezTest\PHPssh2\Auth
{


    use PHPUnit\Framework\TestCase;
    use Tez\PHPssh2\Auth\ISSH2Credentials;
    use Tez\PHPssh2\Auth\SSH2AuthPrivPubKeyFile;
    use Tez\PHPssh2\Connection\SSH2ConnectionResource;
    use Tez\PHPssh2\Exception\SSH2AuthenticationException;

    class SSH2AuthPrivPubKeyFileTest extends TestCase
    {
        public function testInstanceOfISSH2Credentials()
        {
            $this->assertInstanceOf(ISSH2Credentials::class, new SSH2AuthPrivPubKeyFile());
        }

        /**
         * @throws SSH2AuthenticationException
         */
        public function testAuthentication()
        {
            $username = 'user';
            $keyfile = 'privkeypath';
            $pubkeyfile = 'pubkeypath';
            $passphrase = 'password';

            $auth = new SSH2AuthPrivPubKeyFile($username, $keyfile, $pubkeyfile, $passphrase);
            $this->assertNull($auth->authenticate(new SSH2ConnectionResource([])));
        }

        /**
         * @throws SSH2AuthenticationException
         */
        public function testAuthenticationMissingPubKeySSH2AuthenticationException()
        {
            $this->expectException(SSH2AuthenticationException::class);
            $this->expectExceptionMessage('Missing public key path');
            $username = 'user';
            $keyfile = 'privkeypath';
            $pubkeyfile = null;
            $passphrase = 'password';
            $auth = new SSH2AuthPrivPubKeyFile($username, $keyfile, $pubkeyfile, $passphrase);
            $auth->authenticate(new SSH2ConnectionResource([]));
        }

        /**
         * @throws SSH2AuthenticationException
         */
        public function testAuthenticationMissingPrivKeySSH2AuthenticationException()
        {
            $this->expectException(SSH2AuthenticationException::class);
            $this->expectExceptionMessage('Missing private key path');
            $username = 'user';
            $keyfile = null;
            $pubkeyfile = 'pubkeypath';
            $passphrase = 'password';
            $auth = new SSH2AuthPrivPubKeyFile($username, $keyfile, $pubkeyfile, $passphrase);
            $auth->authenticate(new SSH2ConnectionResource([]));
        }

        /**
         * @throws SSH2AuthenticationException
         */
        public function testAuthenticationMissingUsernameSSH2AuthenticationException()
        {
            $this->expectException(SSH2AuthenticationException::class);
            $this->expectExceptionMessage('Missing username');
            $username = null;
            $keyfile = 'keyfile';
            $pubkeyfile = 'pubkeypath';
            $passphrase = 'password';
            $auth = new SSH2AuthPrivPubKeyFile($username, $keyfile, $pubkeyfile, $passphrase);
            $auth->authenticate(new SSH2ConnectionResource([]));
        }


        /**
         * @throws SSH2AuthenticationException
         */
        public function testAuthenticationWrongPassphraseSSH2AuthenticationException()
        {
            $this->expectException(SSH2AuthenticationException::class);
            $this->expectExceptionMessageRegExp('/Authentication for user \w+ not successful/');
            $username = 'user';
            $keyfile = 'keyfile';
            $pubkeyfile = 'pubkeypath';
            $passphrase = 'passwordabc';
            $auth = new SSH2AuthPrivPubKeyFile($username, $keyfile, $pubkeyfile, $passphrase);
            $auth->authenticate(new SSH2ConnectionResource([]));
        }

    }
}