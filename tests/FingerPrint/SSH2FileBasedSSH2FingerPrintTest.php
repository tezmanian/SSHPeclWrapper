<?php
/**
 * PHPssh2 (https://github.com/tezmanian/SSHPeclWrapper)
 *
 * @copyright Copyright (c) 2016 - 2020 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 *
 *
 */

namespace TezTest\PHPssh2\FingerPrint;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamWrapper;
use Tez\PHPssh2\Connection\ISSH2ConnectionResource;
use Tez\PHPssh2\FingerPrint\SSH2FileBasedSSH2FingerPrint;
use Tez\PHPssh2\SSH2;
use TezTest\PHPssh2\ASSH2Test;

class SSH2FileBasedSSH2FingerPrintTest extends ASSH2Test
{

    const DIR = 'fingerPrintDirTest';
    const FILE = 'testPrintTest';

    public function setUp()
    {
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory(self::DIR));
    }

    public function test__construct()
    {
        $tmp = sys_get_temp_dir();
        SSH2FileBasedSSH2FingerPrint::$location = $tmp;

        new SSH2FileBasedSSH2FingerPrint();

        $this->assertEquals($tmp, SSH2FileBasedSSH2FingerPrint::$location);
    }

    public function testCheckFingerPrint()
    {
        $tmp = sys_get_temp_dir();
        SSH2FileBasedSSH2FingerPrint::$location = $tmp;
        $f = new SSH2FileBasedSSH2FingerPrint();
        $ssh = new SSH2($this->SSH2ConnectionMock(), $this->SSH2CredentialMock(), $f);

        $this->assertInstanceOf(ISSH2ConnectionResource::class, $ssh->getConnectionResource());
    }

    public function testFileSystem()
    {
        SSH2FileBasedSSH2FingerPrint::$location = vfsStream::url(self::DIR);
        SSH2FileBasedSSH2FingerPrint::$fileName = self::FILE;

        $sfp = new SSH2FileBasedSSH2FingerPrint();

        $this->assertTrue(vfsStreamWrapper::getRoot()->hasChild(self::FILE));

        $f = new SSH2FileBasedSSH2FingerPrint();
        $ssh = new SSH2($this->SSH2ConnectionMock(), $this->SSH2CredentialMock(), $f);

        $expected = $f->checkFingerPrint();

        $entry = explode("\n", file_get_contents(vfsStreamWrapper::getRoot()->getChild(self::FILE)->url()));
        $actual = explode(' ', $entry[0]);
        $this->assertEquals($expected, $actual[1]);
    }
}
