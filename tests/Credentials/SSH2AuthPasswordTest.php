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
    use Tez\PHPssh2\Credentials\SSH2AuthPassword;
    use Tez\PHPssh2\Exception\SSH2AuthenticationException;
    use Tez\PHPssh2\Exception\SSH2AuthenticationPasswordException;
    use Tez\PHPssh2\Exception\SSH2AuthenticationUsernameException;
    use TezTest\PHPssh2\ASSH2Test;
    use const Tez\PHPssh2\Credentials\SSHTEST_PASSWORD;
    use const Tez\PHPssh2\Credentials\SSHTEST_USERNAME;


    class SSH2AuthPasswordTest extends ASSH2Test
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
            $username = SSHTEST_USERNAME;
            $password = SSHTEST_PASSWORD;

            $auth = new SSH2AuthPassword($username, $password);
            $this->assertNull($auth->authenticate($this->SSH2ConnectionResourceMock(true)));
        }

        /**
         * @throws SSH2AuthenticationException
         */
        public function testAuthenticationMissingPasswordSSH2AuthenticationException()
        {
            $this->expectException(SSH2AuthenticationPasswordException::class);
            $this->expectExceptionMessage('Missing password');
            $username = SSHTEST_USERNAME;

            $auth = new SSH2AuthPassword($username);
            $this->assertNull($auth->authenticate($this->SSH2ConnectionResourceMock(true)));
        }

        /**
         * @throws SSH2AuthenticationException
         */
        public function testAuthenticationMissingUsernameSSH2AuthenticationException()
        {
            $this->expectException(SSH2AuthenticationUsernameException::class);
            $this->expectExceptionMessage('Missing username');
            $auth = new SSH2AuthPassword();
            $this->assertNull($auth->authenticate($this->SSH2ConnectionResourceMock(true)));
        }

        /**
         * @throws SSH2AuthenticationException
         */
        public function testAuthenticationMissingWrongResourceSSH2AuthenticationException()
        {
            $this->expectException(SSH2AuthenticationException::class);
            $this->expectExceptionMessageRegExp('/Authentication for user \w+ not successful/');
            $username = SSHTEST_USERNAME;
            $password = SSHTEST_PASSWORD;

            $auth = new SSH2AuthPassword($username, $password);
            $this->assertNull($auth->authenticate($this->SSH2ConnectionResourceMock(false)));
        }
    }
}