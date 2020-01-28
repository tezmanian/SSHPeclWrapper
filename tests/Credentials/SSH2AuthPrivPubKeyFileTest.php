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


    use org\bovigo\vfs\vfsStream;
    use org\bovigo\vfs\vfsStreamDirectory;
    use org\bovigo\vfs\vfsStreamWrapper;
    use Tez\PHPssh2\Credentials\ISSH2Credentials;
    use Tez\PHPssh2\Credentials\SSH2AuthPrivPubKeyFile;
    use Tez\PHPssh2\Exception\SSH2AuthenticationException;
    use TezTest\PHPssh2\ASSH2Test;
    use const Tez\PHPssh2\Credentials\SSHTEST_PASSWORD;
    use const Tez\PHPssh2\Credentials\SSHTEST_USERNAME;

    class SSH2AuthPrivPubKeyFileTest extends ASSH2Test
    {
        const DIR = 'sshDir';

        public function setUp()
        {
            vfsStreamWrapper::register();
            vfsStreamWrapper::setRoot(new vfsStreamDirectory(self::DIR));
            $root = vfsStream::setup(self::DIR);
            vfsStream::newFile('publickeypath')->at($root)->setContent("publicKey");
            vfsStream::newFile('keypath')->at($root)->setContent("publicKey");
        }

        public function testInstanceOfISSH2Credentials()
        {
            $this->assertInstanceOf(ISSH2Credentials::class, new SSH2AuthPrivPubKeyFile());
        }

        /**
         * @throws SSH2AuthenticationException
         */
        public function testAuthenticationMissingPubKeySSH2AuthenticationException()
        {
            $this->expectException(SSH2AuthenticationException::class);
            $this->expectExceptionMessage('Missing public key path');
            $username = SSHTEST_USERNAME;
            $password = SSHTEST_PASSWORD;
            $keyfile = vfsStreamWrapper::getRoot()->getChild('keypath')->url();
            $pubkeyfile = null;
            $auth = new SSH2AuthPrivPubKeyFile($username, $keyfile, $pubkeyfile, $password);
            $this->assertNull($auth->authenticate($this->SSH2ConnectionResourceMock(true)));
        }

        /**
         * @throws SSH2AuthenticationException
         */
        public function testAuthenticationMissingPrivKeySSH2AuthenticationException()
        {
            $this->expectException(SSH2AuthenticationException::class);
            $this->expectExceptionMessage('Missing private key path');
            $username = SSHTEST_USERNAME;
            $password = SSHTEST_PASSWORD;
            $keyfile = null;
            $pubkeyfile = vfsStreamWrapper::getRoot()->getChild('publickeypath')->url();

            $auth = new SSH2AuthPrivPubKeyFile($username, $keyfile, $pubkeyfile, $password);
            $this->assertNull($auth->authenticate($this->SSH2ConnectionResourceMock(true)));
        }

        /**
         * @throws SSH2AuthenticationException
         */
        public function testAuthenticationMissingUsernameSSH2AuthenticationException()
        {
            $this->expectException(SSH2AuthenticationException::class);
            $this->expectExceptionMessage('Missing username');
            $username = null;
            $password = SSHTEST_PASSWORD;
            $keyfile = vfsStreamWrapper::getRoot()->getChild('keypath')->url();
            $pubkeyfile = vfsStreamWrapper::getRoot()->getChild('publickeypath')->url();

            $auth = new SSH2AuthPrivPubKeyFile($username, $keyfile, $pubkeyfile, $password);
            $this->assertNull($auth->authenticate($this->SSH2ConnectionResourceMock(true)));
        }


        /**
         * @throws SSH2AuthenticationException
         */
        public function testAuthenticationWrongPassphraseSSH2AuthenticationException()
        {
            $this->expectException(SSH2AuthenticationException::class);
            $this->expectExceptionMessageRegExp('/Authentication for user \w+ not successful/');

            $username = SSHTEST_USERNAME;
            $password = SSHTEST_PASSWORD . 'abc';
            $keyfile = vfsStreamWrapper::getRoot()->getChild('keypath')->url();
            $pubkeyfile = vfsStreamWrapper::getRoot()->getChild('publickeypath')->url();

            $auth = new SSH2AuthPrivPubKeyFile($username, $keyfile, $pubkeyfile, $password);
            $this->assertNull($auth->authenticate($this->SSH2ConnectionResourceMock(true)));
        }

        /**
         * @throws SSH2AuthenticationException
         */
        public function testAuthentication()
        {
            $username = SSHTEST_USERNAME;
            $password = SSHTEST_PASSWORD;
            $keyfile = vfsStreamWrapper::getRoot()->getChild('keypath')->url();
            $pubkeyfile = vfsStreamWrapper::getRoot()->getChild('publickeypath')->url();

            $auth = new SSH2AuthPrivPubKeyFile($username, $keyfile, $pubkeyfile, $password);
            $this->assertNull($auth->authenticate($this->SSH2ConnectionResourceMock(true)));
        }

    }
}