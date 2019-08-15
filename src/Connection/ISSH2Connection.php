<?php

/**
 * PHPssh2 (https://github.com/tezmanian/PHP-ssh)
 *
 * @copyright Copyright (c) 2016-2019 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 */

namespace Tez\PHPssh2\Connection;

use Tez\PHPssh2\Exception\SSH2ConnectionException;

/**
 * Interface ISSH2Connection
 * @package Tez\PHPssh2\Connection
 */
interface ISSH2Connection
{

    /**
     * array
     */
    const METHODS = [
        'kex' => 'diffie-hellman-group1-sha1,diffie-hellman-group14-sha1,diffie-hellman-group-exchange-sha1,diffie-hellman-group-exchange-sha256',
        'client_to_server' =>
            [
                'crypt' => 'aes256-ctr,aes192-ctr,aes128-ctr,aes256-cbc,aes192-cbc,aes128-cbc,3des-cbc,blowfish-cbc,cast128-cbc,arcfour,arcfour128,none',
                'comp' => 'zlib,none'
            ],
        'server_to_client' =>
            [
                'crypt' => 'aes256-ctr,aes192-ctr,aes128-ctr,aes256-cbc,aes192-cbc,aes128-cbc,3des-cbc,blowfish-cbc,cast128-cbc,arcfour,arcfour128,none',
                'comp' => 'zlib,none'
            ]
    ];

    /**
     * Set host
     *
     * @param string $host
     * @return ISSH2Connection
     */
    public function setHost(string $host): ISSH2Connection;

    /**
     * Set port
     *
     * @param int $port
     * @return ISSH2Connection
     */
    public function setPort(int $port): ISSH2Connection;

    /**
     * Returns a SSH2ConnectionResource
     *
     * return SSH2ConnectionResource
     * @throws SSH2ConnectionException
     */
    public function connect(): ISSH2ConnectionResource;
}
