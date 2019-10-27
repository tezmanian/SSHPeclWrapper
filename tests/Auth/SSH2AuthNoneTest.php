<?php

/**
 * PHPssh2 (https://github.com/tezmanian/SSHPeclWrapper)
 *
 * @copyright Copyright (c) 2016-2019 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 */

namespace Tez\PHPssh2\Auth
{
    function ssh2_auth_none($session, string $username)
    {
        if (is_array($session) && is_string($username))
        {
            return true;
        }
        return ['some session', 'some username'];
    }
}

namespace TezTest\PHPssh2\Auth
{

    use PHPUnit\Framework\TestCase;
    use Tez\PHPssh2\Auth\ISSH2Credentials;
    use Tez\PHPssh2\Auth\SSH2AuthNone;
    use Tez\PHPssh2\Connection\SSH2ConnectionResource;
    use Tez\PHPssh2\Exception\SSH2AuthenticationException;
    use Tez\PHPssh2\Exception\SSH2AuthenticationUsernameException;

    class SSH2AuthNoneTest extends TestCase
    {
        public function testInstanceOfISSH2Credentials()
        {
            $this->assertInstanceOf(ISSH2Credentials::class, new SSH2AuthNone());
        }

        /**
         * @throws SSH2AuthenticationException
         */
        public function testGetUsernameSSH2AuthenticationException()
        {
            $this->expectException(SSH2AuthenticationUsernameException::class);
            $this->expectExceptionMessage('Missing username');
            $auth = new SSH2AuthNone();
            $auth->getUsername();
        }

        /**
         * @throws SSH2AuthenticationException
         */
        public function testGetUsername()
        {
            $username = 'user';
            $auth = new SSH2AuthNone($username);
            $this->assertEquals($username, $auth->getUsername());
        }

        /**
         * @throws SSH2AuthenticationException
         */
        public function testAuthentication()
        {
            $username = 'user';
            $auth = new SSH2AuthNone($username);
            $this->assertNull($auth->authenticate(new SSH2ConnectionResource([])));
        }

        /**
         * @throws SSH2AuthenticationException
         */
        public function testAuthenticationUsernameSSH2AuthenticationException()
        {
            $this->expectException(SSH2AuthenticationUsernameException::class);
            $this->expectExceptionMessage('Missing username');
            $auth = new SSH2AuthNone();
            $auth->authenticate(new SSH2ConnectionResource(''));
        }

        /**
         * @throws SSH2AuthenticationException
         */
        public function testAuthenticationSSH2AuthenticationException()
        {
            $this->expectException(SSH2AuthenticationException::class);
            $this->expectExceptionMessageRegExp('/Authentication not successful, following methods allowed/');
            $username = 'user';
            $auth = new SSH2AuthNone($username);
            $auth->authenticate(new SSH2ConnectionResource(''));
        }

    }
}