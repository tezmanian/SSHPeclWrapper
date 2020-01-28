<?php
/**
 * PHPssh2 (https://github.com/tezmanian/SSHPeclWrapper)
 *
 * @copyright Copyright (c) 2016 - 2020 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 *
 *
 */


namespace Tez\PHPssh2\Credentials {
    const SSHTEST_USERNAME = 'WaldiWeich';
    const SSHTEST_PASSWORD = 'WaldisPwd';

    function ssh2_auth_none($session, string $username)
    {
        if ($session == true && $username == SSHTEST_USERNAME) {
            return true;
        }
        return ['some session', 'some username'];
    }

    function ssh2_auth_password($session, string $username, string $password): bool
    {
        if ($session == true && $username == SSHTEST_USERNAME && $password == SSHTEST_PASSWORD) {
            return true;
        }
        return false;
    }

    function ssh2_auth_pubkey_file($session, string $username, string $pubkeyfile, string $privkeyfile, string $passphrase = ''): bool
    {
        if ($session == true
            && $username == SSHTEST_USERNAME
            && is_string($pubkeyfile)
            && is_string($privkeyfile)
            && (empty($passphrase) || (is_string($passphrase) && $passphrase == SSHTEST_PASSWORD))) {
            return true;
        }
        return false;
    }

}

namespace Tez\PHPssh2\FingerPrint {

    const ENC_VALUE = 'abcdefg';

    function ssh2_fingerprint($resource, $flags)
    {
        if ($resource == true) {
            switch ($flags | ISSH2FingerPrintOutput::HEX) {
                case ISSH2FingerPrintEncryption::MD5:
                    return md5(ENC_VALUE);
                    break;
                case ISSH2FingerPrintEncryption::SHA1:
                    return sha1(ENC_VALUE);
                    break;
                default:
                    return false;
            }
        }
        return false;
    }
}

namespace TezTest\PHPssh2 {


    use PHPUnit\Framework\TestCase;
    use Tez\PHPssh2\Connection\SSH2Connection;
    use Tez\PHPssh2\Connection\SSH2ConnectionResource;
    use Tez\PHPssh2\Credentials\SSH2AuthNone;
    use Tez\PHPssh2\Exception\SSH2AuthenticationException;
    use Tez\PHPssh2\Exception\SSH2Exception;
    use Tez\PHPssh2\SSH2;

    /**
     * Class ASSH2AuthTest
     * @package TezTest\PHPssh2\Auth
     */
    abstract class ASSH2Test extends TestCase
    {

        /**
         * @return SSH2
         * @throws SSH2AuthenticationException
         * @throws SSH2Exception
         */
        protected function mockSSH2()
        {
            return new SSH2($this->SSH2ConnectionMock(), $this->SSH2CredentialMock());
        }

        protected function SSH2ConnectionMock(bool $return = true)
        {
            $sshConnection = $this->getMockBuilder(SSH2Connection::class)
                ->disableOriginalConstructor()
                ->getMock();
            $sshConnection->expects($this->any())->method('connect')->willReturn($this->SSH2ConnectionResourceMock($return));

            return $sshConnection;
        }

        protected function SSH2ConnectionResourceMock(bool $return = true)
        {
            $mock = $this->getMockBuilder(SSH2ConnectionResource::class)
                ->disableOriginalConstructor()
                ->getMock();
            $mock->method('getSession')->willReturn($return);
            $mock->method('getHost')->willReturn('localhost');
            return $mock;
        }

        protected function SSH2CredentialMock()
        {
            $sshCredentials = $this->getMockBuilder(SSH2AuthNone::class)
                ->disableOriginalConstructor()
                ->getMock();
            $sshCredentials->expects($this->any())->method('authenticate')->willReturn(null);

            return $sshCredentials;
        }
    }
}