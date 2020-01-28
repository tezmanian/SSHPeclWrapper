<?php

/**
 *
 * PHPssh2 (https://github.com/tezmanian/SSHPeclWrapper)
 *
 * @copyright Copyright (c) 2016 - 2020 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 *
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

    use Tez\PHPssh2\Connection\ISSH2ConnectionResource;
    use Tez\PHPssh2\Exception\SSH2AuthenticationException;
    use Tez\PHPssh2\Exception\SSH2Exception;
    use Tez\PHPssh2\ISSH2;
    use Tez\PHPssh2\SCP\ISCP;
    use Tez\PHPssh2\SFTP\SFTP;

    class SSH2Test extends ASSH2Test
    {

        /**
         * @throws SSH2AuthenticationException
         * @throws SSH2Exception
         */
        public function testGetConnectionResource()
        {

            $ssh = $this->mockSSH2();

            $this->assertInstanceOf(ISSH2ConnectionResource::class, $ssh->getConnectionResource());
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
        public function testGetSCP()
        {
            $scp = $this->mockSSH2()->getSCP();
            $this->assertInstanceOf(ISCP::class, $scp);
        }
    }
}