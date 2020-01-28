<?php

/**
 *
 * PHPssh2 (https://github.com/tezmanian/SSHPeclWrapper)
 *
 * @copyright Copyright (c) 2016 - 2020 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 *
 */

namespace TezTest\PHPssh2\Credentials {

    use Tez\PHPssh2\Credentials\ISSH2Credentials;
    use Tez\PHPssh2\Credentials\SSH2AuthNone;
    use Tez\PHPssh2\Exception\SSH2AuthenticationException;
    use Tez\PHPssh2\Exception\SSH2AuthenticationUsernameException;
    use TezTest\PHPssh2\ASSH2Test;
    use const Tez\PHPssh2\Credentials\SSHTEST_USERNAME;

    class SSH2AuthNoneTest extends ASSH2Test
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


        public function testAuthentication()
        {
            $username = SSHTEST_USERNAME;

            $auth = new SSH2AuthNone($username);
            $this->assertNull($auth->authenticate($this->SSH2ConnectionResourceMock()));

        }

        /**
         * @throws SSH2AuthenticationException
         */
        public function testAuthenticationUsernameSSH2AuthenticationException()
        {

            $this->expectException(SSH2AuthenticationUsernameException::class);
            $this->expectExceptionMessage('Missing username');
            $auth = new SSH2AuthNone();
            $this->assertNull($auth->authenticate($this->SSH2ConnectionResourceMock()));
        }

        /**
         * @throws SSH2AuthenticationException
         */
        public function testAuthenticationSSH2AuthenticationException()
        {
            $this->expectException(SSH2AuthenticationException::class);
            $this->expectExceptionMessageRegExp('/Authentication not successful, following methods allowed/');
            $username = SSHTEST_USERNAME;
            $auth = new SSH2AuthNone($username);
            $this->assertNull($auth->authenticate($this->SSH2ConnectionResourceMock(false)));
        }

    }
}