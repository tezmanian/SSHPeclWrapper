<?php

/**
 *
 * PHPssh2 (https://github.com/tezmanian/SSHPeclWrapper)
 *
 * @copyright Copyright (c) 2016 - 2020 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 *
 */

namespace Tez\PHPssh2\Tools;


class PermissionCalculator
{

    const RIGHTS = [
        'user' => [
            'r' => 0x0100,
            'w' => 0x0080,
            'x' => 0x0040,
            's' => 0x0800,
        ],
        'group' => [
            'r' => 0x0020,
            'w' => 0x0010,
            'x' => 0x0008,
            's' => 0x0400,
        ],
        'other' => [
            'r' => 0x0004,
            'w' => 0x0002,
            'x' => 0x0001,
            's' => 0x0200,
        ],
    ];

    const TYPE = [
        's' => 0xC000,
        'l' => 0xA000,
        '-' => 0x8000,
        'b' => 0x6000,
        'd' => 0x4000,
        'c' => 0x2000,
        'p' => 0x1000,
    ];

    /**
     * returns object unix permissions
     * @param int $perms
     * @return string
     */
    public static function getPermissions(int $perms): string
    {
        return self::getType($perms) . self::getRights($perms);
    }

    /**
     * returns object unix type
     * @param int $perms
     * @return string
     */
    public static function getType(int $perms): string
    {
        foreach (self::TYPE as $type => $bit) {
            if (($perms & $bit) == $bit) {
                return $type;
            }
        }
        return 'u';
    }

    /**
     * returns object unix rights
     * @param int $perms
     * @return string
     */
    public static function getRights(int $perms): string
    {
        $info = [];
        foreach (self::RIGHTS as $who) {
            $info[] = (($perms & $who['r']) ? 'r' : '-');
            $info[] = (($perms & $who['w']) ? 'w' : '-');
            $info[] = (($perms & $who['x']) ? (($perms & $who['s']) ? 's' : 'x') : (($perms & $who['s']) ? 'S' : '-'));
        }
        return implode($info);
    }
}