<?php
/**
 * PHPssh2 (https://github.com/tezmanian/SSHPeclWrapper)
 *
 * @copyright Copyright (c) 2016 - 2020 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 *
 *
 */

namespace TezTest\PHPssh2\FingerPrint {

    use Tez\PHPssh2\Exception\SSH2FingerPrintException;
    use Tez\PHPssh2\FingerPrint\ISSH2FingerPrintEncryption;
    use Tez\PHPssh2\FingerPrint\SSH2FingerPrint;
    use TezTest\PHPssh2\ASSH2Test;
    use const Tez\PHPssh2\FingerPrint\ENC_VALUE;

    class SSH2FingerPrintTest extends ASSH2Test
    {

        public function testSetEncryptionMethod()
        {
            $f = new SSH2FingerPrint();

            $f->setEncryptionMethod(ISSH2FingerPrintEncryption::MD5);

            $this->assertEquals('MD5', $f->getEncryptionMethod());

            $this->expectException(SSH2FingerPrintException::class);
            $this->expectExceptionMessage('unkown encryption method');

            $f->setEncryptionMethod(5);
        }

        public function testGetEncryptionMethod()
        {
            $f = new SSH2FingerPrint();

            $this->assertEquals('SHA1', $f->getEncryptionMethod());
        }

        public function testCheckFingerPrint()
        {
            $f = new SSH2FingerPrint();
            $f->setISSH2ConnectionResource($this->SSH2ConnectionResourceMock());
            $this->assertEquals(sha1(ENC_VALUE), $f->checkFingerPrint());

            $f->setEncryptionMethod(ISSH2FingerPrintEncryption::MD5);
            $this->assertEquals(md5(ENC_VALUE), $f->checkFingerPrint());
        }

        public function testSetFingerPrint()
        {
            $f = new SSH2FingerPrint();
            $f->setISSH2ConnectionResource($this->SSH2ConnectionResourceMock());
            $f->setFingerPrint(sha1(ENC_VALUE));
            $this->assertEquals(sha1(ENC_VALUE), $f->checkFingerPrint());
        }

        public function testCheckFingerPrintException()
        {
            $f = new SSH2FingerPrint();
            $f->setISSH2ConnectionResource($this->SSH2ConnectionResourceMock());
            $f->setFingerPrint(md5(ENC_VALUE));
            $this->expectException(SSH2FingerPrintException::class);
            $this->expectExceptionMessage('Hostkey missmatch: Possible man in middle attack');
            $f->checkFingerPrint();
        }
    }
}