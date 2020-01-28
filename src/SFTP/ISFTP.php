<?php

/**
 *
 * PHPssh2 (https://github.com/tezmanian/SSHPeclWrapper)
 *
 * @copyright Copyright (c) 2016 - 2020 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 *
 */

namespace Tez\PHPssh2\SFTP;

use Tez\PHPssh2\Exception\SFTPException;

/**
 * Interface ISFTP
 * @package Tez\PHPssh2
 */
interface ISFTP
{

    /**
     * returns the remote path
     *
     * @return string
     */
    public function pwd(): string;

    /**
     * returns the directory of a given path
     *
     * @param string $path
     * @return array
     */
    public function ls(string $path = ''): array;

    /**
     * put a file to the server
     *
     * @param string $source
     * @param string $target
     * @param bool $overwrite
     * @throws SFTPException
     */
    public function put(string $source = '', string $target = '', bool $overwrite = false): void;

    /**
     * fetch a file from the server
     *
     * @param string $remote
     * @param string $local
     * @param bool $overwrite
     * @throws SFTPException
     */
    public function get(string $remote = '', string $local = '', bool $overwrite = false): void;

    /**
     * get the stats of a remote path.
     *
     * @param string $path
     * @return array
     */
    public function stat(string $path): array;

    /**
     * creates dir on remote server
     *
     * @param string $path
     * @param int $mode
     * @param bool $recursive
     * @throws SFTPException
     */
    public function mkdir(string $path, int $mode = 0777, bool $recursive = false): void;

    /**
     * chmod on remote site
     *
     * @param string $path
     * @param int $mode
     * @throws SFTPException
     */
    public function chmod(string $path, int $mode): void;

    /**
     * returns unix filesystem like human readable timestamp.
     *
     * @param string $path
     * @return array
     * @throws SFTPException
     */
    public function rawlist(string $path = ''): array;

    /**
     * @param string $path
     * @throws SFTPException
     */
    public function delete(string $path): void;

    /**
     * returns size of remote file.
     *
     * @param string $path
     * @return int
     * @throws SFTPException
     */
    public function fileSize(string $path): int;

    /**
     * renames remote files or directories.
     *
     * @param string $old_path
     * @param string $new_path
     * @param bool $overwrite
     * @return bool
     * @throws SFTPException
     */
    public function rename($old_path, $new_path, $overwrite = false): bool;

    /**
     * check if file exists on remote server
     *
     * @param string $path
     * @return bool
     * @throws SFTPException
     */
    public function fileExist(string $path): bool;

    /**
     * creates symlink of remote files.
     *
     * @param string $old_path
     * @param string $new_path
     * @return bool
     * @throws SFTPException
     */
    public function symlink($old_path, $new_path): bool;

    /**
     * change remote working directory.
     *
     * @param string $path
     * @return string
     * @throws SFTPException
     */
    public function cd(string $path): string;

    /**
     * check if path is directory
     *
     * @param $path
     * @return bool
     * @throws SFTPException
     */
    public function is_dir($path): bool;

    /**
     * returns extended functions
     *
     * @return ISFTPExtended
     */
    public function getExtended(): ISFTPExtended;
}
