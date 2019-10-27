<?php

/**
 * PHPssh2 (https://github.com/tezmanian/SSHPeclWrapper)
 *
 * @copyright Copyright (c) 2016-2019 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 */

namespace Tez\PHPssh2\Auth
{
    function ssh2_auth_password($session, string $username, string $password): bool
    {
        if (is_array($session) && is_string($username) && is_string($password))
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
    use Tez\PHPssh2\Auth\SSH2AuthPassword;
    use Tez\PHPssh2\Connection\SSH2ConnectionResource;
    use Tez\PHPssh2\Exception\SSH2AuthenticationException;
    use Tez\PHPssh2\Exception\SSH2AuthenticationPasswordException;
    use Tez\PHPssh2\Exception\SSH2AuthenticationUsernameException;

    class SSH2AuthPasswordTest extends TestCase
    {
        public function testInstanceOfISSH2Credentials()
        {
            $this->assertInstanceOf(ISSH2Credentials::class, new SSH2AuthPassword());
        }

        /**
         * @throws SSH2AuthenticationException
         */
        public function testGetUsernameSSH2AuthenticationException()
        {
            $this->expectException(SSH2AuthenticationUsernameException::class);
            $this->expectExceptionMessage('Missing username');
            $auth = new SSH2AuthPassword();
            $auth->getUsername();
        }

        /**
         * @throws SSH2AuthenticationException
         */
        public function testAuthentication()
        {
            $username = 'user';
            $password = 'password';

            $auth = new SSH2AuthPassword($username, $password);
            $this->assertNull($auth->authenticate(new SSH2ConnectionResource([])));
        }

        /**
         * @throws SSH2AuthenticationException
         */
        public function testAuthenticationMissingPasswordSSH2AuthenticationException()
        {
            $this->expectException(SSH2AuthenticationPasswordException::class);
            $this->expectExceptionMessage('Missing password');
            $username = 'user';

            $auth = new SSH2AuthPassword($username);
            $auth->authenticate(new SSH2ConnectionResource([]));
        }

        /**
         * @throws SSH2AuthenticationException
         */
        public function testAuthenticationMissingUsernameSSH2AuthenticationException()
        {
            $this->expectException(SSH2AuthenticationUsernameException::class);
            $this->expectExceptionMessage('Missing username');
            $auth = new SSH2AuthPassword();
            $auth->authenticate(new SSH2ConnectionResource([]));
        }

        /**
         * @throws SSH2AuthenticationException
         */
        public function testAuthenticationMissingWrongResourceSSH2AuthenticationException()
        {
            $this->expectException(SSH2AuthenticationException::class);
            $this->expectExceptionMessageRegExp('/Authentication for user \w+ not successful/');
            $username = 'user';
            $password = 'password';

            $auth = new SSH2AuthPassword($username, $password);
            $auth->authenticate(new SSH2ConnectionResource(''));
        }
    }
}