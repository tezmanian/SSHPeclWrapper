<?php

/**
 *
 * PHPssh2 (https://github.com/tezmanian/SSHPeclWrapper)
 *
 * @copyright Copyright (c) 2016 - 2020 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 *
 */

namespace Tez\PHPssh2\Connection
{
    function ssh2_connect(string $host, int $port = 22, array $methods = [], array $callbacks = []): bool
    {
        if ($host == 'localhost' && $port == 22 && is_array($methods) && is_array($callbacks)) {
            return true;
        }
        return false;
    }
}

namespace TezTest\PHPssh2\Connection
{

    use PHPUnit\Framework\TestCase;
    use Tez\PHPssh2\Connection\ISSH2Connection;
    use Tez\PHPssh2\Connection\SSH2Connection;
    use Tez\PHPssh2\Exception\SSH2ConnectionException;

    class SSH2ConnectionTest extends TestCase
    {
        public function testInstanceOfSSH2()
        {
            $this->assertInstanceOf(ISSH2Connection::class, new SSH2Connection());
        }


        public function testEmptyHost()
        {
            $this->expectException(SSH2ConnectionException::class);
            $conn = new SSH2Connection();
            $conn->getHost();

        }

        /**
         * @throws SSH2ConnectionException
         */
        public function testHost()
        {
            $host = 'localhost';
            $conn = new SSH2Connection($host);
            $this->assertEquals($host, $conn->getHost());
        }

        /**
         * @throws SSH2ConnectionException
         */
        public function testHostAndPort()
        {
            $host = 'localhost';
            $conn = new SSH2Connection($host);
            $this->assertEquals($host, $conn->getHost());
            $this->assertEquals(22, $conn->getPort());

            $conn->setPort(23);
            $this->assertEquals(23, $conn->getPort());
        }

    }
}