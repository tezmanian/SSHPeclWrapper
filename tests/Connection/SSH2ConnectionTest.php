<?php

/**
 * PHPssh2 (https://github.com/tezmanian/SSHPeclWrapper)
 *
 * @copyright Copyright (c) 2016-2019 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 */

namespace Tez\PHPssh2\Connection
{
    function ssh2_connect(string $host, int $port = 22, array $methods = [], array $callbacks = []): array
    {
        return [
            'host' => $host,
            'port' => $port,
            'methods' => $methods,
            'callbacks' => $callbacks,
        ];

    }
}

namespace TezTest\PHPssh2\Connection
{

    use PHPUnit\Framework\TestCase;
    use Tez\PHPssh2\Connection\ISSH2Connection;
    use Tez\PHPssh2\Connection\ISSH2ConnectionResource;
    use Tez\PHPssh2\Connection\SSH2Connection;
    use Tez\PHPssh2\Exception\SSH2ConnectionException;

    class SSH2ConnectionTest extends TestCase
    {
        public function testInstanceOfSSH2()
        {
            $this->assertInstanceOf(ISSH2Connection::class, new SSH2Connection());
        }

        /**
         * @expectedException \Tez\PHPssh2\Exception\SSH2ConnectionException
         */
        public function testEmptyHost()
        {

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

        /**
         * @throws SSH2ConnectionException
         */
        public function testConnect()
        {
            $host = 'localhost';
            $conn = new SSH2Connection($host);

            $ssh_conn = $conn->connect();
            $this->assertInstanceOf(ISSH2ConnectionResource::class, $ssh_conn);

            $mock = $ssh_conn->getConnection();
            $this->assertIsArray($mock);
            $this->assertContains($host, $mock);
            $this->assertContains(22, $mock);
            $this->assertContains(ISSH2Connection::METHODS, $mock);
        }
    }
}