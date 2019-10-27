<?php

/**
 * PHPssh2 (https://github.com/tezmanian/SSHPeclWrapper)
 *
 * @copyright Copyright (c) 2016-2019 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 */

namespace Tez\PHPssh2\SFTP
{
    function ssh2_sftp($resource)
    {
        return $resource;
    }
}

namespace TezTest\PHPssh2
{

    use PHPUnit\Framework\TestCase;
    use Tez\PHPssh2\Auth\SSH2AuthNone;
    use Tez\PHPssh2\Connection\ISSH2ConnectionResource;
    use Tez\PHPssh2\Connection\SSH2Connection;
    use Tez\PHPssh2\Exception\SSH2AuthenticationException;
    use Tez\PHPssh2\Exception\SSH2Exception;
    use Tez\PHPssh2\ISSH2;
    use Tez\PHPssh2\SCP\ISCP;
    use Tez\PHPssh2\SFTP\SFTP;
    use Tez\PHPssh2\SFTP\SFTPExtended;
    use Tez\PHPssh2\SSH2;

    class SSH2Test extends TestCase
    {

        /**
         * @throws SSH2AuthenticationException
         * @throws SSH2Exception
         */
        public function testGetConnectionResource()
        {
            $conn = $this->mockSSH2()->getConnectionResource();
            $this->assertInstanceOf(ISSH2ConnectionResource::class, $conn);
        }

        /**
         * @return SSH2
         * @throws SSH2AuthenticationException
         * @throws SSH2Exception
         */
        private function mockSSH2()
        {
            return new SSH2(new SSH2Connection('localhost'), new SSH2AuthNone('user'));
        }

        /**
         * @throws SSH2AuthenticationException
         * @throws SSH2Exception
         */
        public function test__construct()
        {
            $this->assertInstanceOf(ISSH2::class, $this->mockSSH2());
        }

        /**
         * @throws SSH2AuthenticationException
         * @throws SSH2Exception
         */
        public function testGetSFTP()
        {
            $sftp = $this->mockSSH2()->getSFTP();
            $this->assertInstanceOf(SFTP::class, $sftp);
        }

        /**
         * @throws SSH2AuthenticationException
         * @throws SSH2Exception
         */
        public function testGetSFTPExtended()
        {
            $sftp = $this->mockSSH2()->getSFTPExtended();
            $this->assertInstanceOf(SFTPExtended::class, $sftp);
        }

        /**
         * @throws SSH2AuthenticationException
         * @throws SSH2Exception
         */
        public function testGetSCP()
        {
            $scp = $this->mockSSH2()->getSCP();
            $this->assertInstanceOf(ISCP::class, $scp);
        }

        /**
         * @throws SSH2AuthenticationException
         * @throws SSH2Exception
         */
        public function testGetConnection()
        {

            $conn = $this->mockSSH2()->getConnection();
            $this->assertIsArray($conn);
            $this->assertContains('localhost', $conn);
            $this->assertContains(22, $conn);

        }

        /**
         * @throws SSH2AuthenticationException
         * @throws SSH2Exception
         */
        public function testAuthentication()
        {
            $this->assertInstanceOf(ISSH2::class, $this->mockSSH2()->authentication(new SSH2AuthNone('user')));
        }
    }
}