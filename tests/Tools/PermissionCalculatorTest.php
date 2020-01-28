<?php

/**
 *
 * PHPssh2 (https://github.com/tezmanian/SSHPeclWrapper)
 *
 * @copyright Copyright (c) 2016 - 2020 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 *
 */

namespace TezTest\PHPssh2\Tools;

use PHPUnit\Framework\TestCase;
use Tez\PHPssh2\Tools\PermissionCalculator;

class PermissionCalculatorTest extends TestCase
{

    public function testGetType()
    {
        $this->assertEquals('d', PermissionCalculator::getType(16877));
        $this->assertEquals('-', PermissionCalculator::getType(33188));
        $s = PermissionCalculator::TYPE['s'];
        $this->assertEquals('s', PermissionCalculator::getType($s));
    }

    public function testGetRights()
    {
        $this->assertEquals('rwxr-xr-x', PermissionCalculator::getRights(16877));
        $user = PermissionCalculator::RIGHTS['user'];
        $group = PermissionCalculator::RIGHTS['group'];
        $other = PermissionCalculator::RIGHTS['other'];
        $res = $user['r'] + $group['w'] + $other['x'];
        $this->assertEquals('r---w---x', PermissionCalculator::getRights($res));


    }

    public function testGetPermissions()
    {
        $this->assertEquals('drwxr-xr-x', PermissionCalculator::getPermissions(16877));
    }


}
