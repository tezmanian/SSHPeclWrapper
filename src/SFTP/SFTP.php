<?php

/**
 * PHPssh2 (https://github.com/tezmanian/SSHPeclWrapper)
 *
 * @copyright Copyright (c) 2016-2019 René Halberstadt
 * @license   https://opensource.org/licenses/Apache-2.0
 */

namespace Tez\PHPssh2\SFTP;

use Tez\PHPssh2\Exception\SFTPException;
use Tez\PHPssh2\ISSH2;
use Tez\PHPssh2\Tools\PermissionCalculator;

/**
 * Class SFTP
 * @package Tez\PHPssh2
 */
class SFTP extends ASFTP implements ISFTP
{

    public function __construct(ISSH2 $ssh2)
    {
        parent::__construct($ssh2);
    }

    /**
     * returns the listing of a given remote directory.
     *
     * @param string $path
     * @return array
     * @throws SFTPException
     */
    public function ls(string $path = ''): array
    {
        $_dir = $this->_dir($path);

        foreach ($_dir as $_key => $_entry)
        {
            if (substr("$_entry", 0, 1) == ".")
            {
                unset($_dir[$_key]);
            }
        }
        return array_values($_dir);
    }

    /**
     * Put a file to remote server
     *
     * @param string $source
     * @param string $target
     * @param bool $overwrite
     * @throws SFTPException
     */
    public function put(string $source = '', string $target = '', bool $overwrite = false): void
    {
        if ($overwrite == true)
        {
            $mode = 'w';
        } else
        {
            $mode = 'x';
        }
        $stream = @fopen($this->_sftpGetPath($target), $mode);
        if (!$stream)
        {
            throw new SFTPException("Could not open file: $target");
        }

        $handle = fopen($source, "rb");
        if (false === $handle)
        {
            throw new SFTPException("Could not open local file: $source.");
        }

        while (!feof($handle))
        {
            $content = fread($handle, 8192);
            fwrite($stream, $content, 8192);
        }
        fclose($handle);
        fclose($stream);

    }

    /**
     * @param string $remote
     * @param string $local
     * @param bool $overwrite
     * @throws SFTPException
     */
    public function get(string $remote = '', string $local = '', bool $overwrite = false): void
    {

        if ($overwrite == true)
        {
            $mode = 'w';
        } else
        {
            $mode = 'x';
        }

        $stream = @fopen($this->_sftpGetPath($remote), 'r');
        if (!$stream)
        {
            throw new SFTPException("Could not open file: $remote");
        }

        $local = @fopen($local, $mode);

        while (!feof($stream))
        {
            $content = fread($stream, 8192);
            fwrite($local, $content, 8192);
        }
        fclose($stream);
        fclose($local);
    }

    /**
     * create directory on remote server
     *
     * @param string $path
     * @param int $mode
     * @param bool $recursive
     * @throws SFTPException
     */
    public function mkdir(string $path, int $mode = 0777, bool $recursive = false): void
    {
        if (!ssh2_sftp_mkdir($this->_getSFTPResource(), $this->_getPath($path), $mode, $recursive))
        {
            throw new SFTPException('could not create directory');
        }
    }

    /**
     * chmod on remote server
     *
     * @param string $path
     * @param int $mode
     * @throws SFTPException
     */
    public function chmod(string $path, int $mode): void
    {
        if (!ssh2_sftp_chmod($this->_getSFTPResource(), $this->_getPath($path), $mode))
        {
            throw new SFTPException('could not change rights');
        }
    }

    /**
     * returns unix filesystem like human readable timestamp.
     *
     * @param string $path
     * @return array
     * @throws SFTPException
     */
    public function rawlist(string $path = ''): array
    {
        $_dir = [];
        foreach ($this->_dir($path) as $_entry)
        {
            $_stat = $this->stat(sprintf('%s/%s', $path, $_entry));
            foreach (array_keys($_stat) as $_key)
            {
                if (is_numeric($_key))
                {
                    unset($_stat[$_key]);
                }
            }
            $_stat['path'] = $_entry;
            $_stat['mode_readable'] = PermissionCalculator::getPermissions($_stat['mode']);
            $_stat['mtime_readable'] = $this->_timeStampToDate($_stat['mtime']);
            $_stat['atime_readable'] = $this->_timeStampToDate($_stat['atime']);
            $_dir[] = $_stat;
        }
        return $_dir;
    }

    /**
     * get the stats of a remote path.
     *
     * @param string $path
     * @return array
     * @throws SFTPException
     */
    public function stat(string $path): array
    {
        $_path = $this->_getPath($path);
        return ssh2_sftp_stat($this->_getSFTPResource(), $_path);
    }

    /**
     * delete remote file.
     *
     * @param string $path
     * @throws SFTPException
     */
    public function delete(string $path): void
    {
        if (!ssh2_sftp_unlink($this->_getSFTPResource(), $this->_getPath($path)))
        {
            throw new SFTPException('could not delete path');
        }
    }

    /**
     * wrapper for delete
     *
     * @param string $path
     * @throws SFTPException
     */
    public function rm(string $path): void
    {
        $this->delete($path);
    }

    /**
     * @param string $path
     * @param bool $recursive
     * @return bool
     * @throws SFTPException
     */
    public function rmdir(string $path, bool $recursive = false): bool
    {

        if ($recursive) {
            foreach ($this->ls($path) as $file) {
                $full = $path . '/' . $file;
                if ($this->is_dir($file)) {
                    $this->rmdir($full, true);
                } else {
                    $this->rm($full);
                }
            }
        }

        return ssh2_sftp_rmdir($this->_getSFTPResource(), $this->_getPath($path));
    }

    /**
     * returns size of remote file.
     *
     * @param string $path
     * @return int
     * @throws SFTPException
     */
    public function fileSize(string $path): int
    {
        return filesize($this->_sftpGetPath($path));
    }

    /**
     * returns real path of remote file or directory.
     *
     * @param string $path
     * @return string
     * @throws SFTPException
     */
    public function realpath($path): string
    {
        return $this->_realpath($this->_getPath($path));
    }

    /**
     * renames remote files or directories.
     *
     * @param string $old_path
     * @param string $new_path
     * @param bool $overwrite
     * @return bool
     * @throws SFTPException
     */
    public function rename($old_path, $new_path, $overwrite = false): bool
    {
        if (false != $overwrite && false != $this->fileExist($new_path))
        {
            $this->delete($new_path);
        }
        return ssh2_sftp_rename($this->_getSFTPResource(), $this->_getPath($old_path), $this->_getPath($new_path));
    }

    /**
     * check if file exists on remote server
     *
     * @param string $path
     * @return bool
     * @throws SFTPException
     */
    public function fileExist(string $path): bool
    {
        return file_exists($this->_sftpGetPath($path));
    }

    /**
     * creates symlink of remote files.
     *
     * @param string $old_path
     * @param string $new_path
     * @return bool
     * @throws SFTPException
     */
    public function symlink($old_path, $new_path): bool
    {
        return ssh2_sftp_symlink($this->_getSFTPResource(), $this->_getPath($old_path), $this->_getPath($new_path));
    }

    /**
     * change remote working directory.
     *
     * @param string $path
     * @return string
     * @throws SFTPException
     */
    public function cd(string $path): string
    {
        $pwd = $this->_getPath($path);
        if (empty($pwd))
        {
            $pwd = "/";
        }
        if ($this->_checkIfExist($pwd) && $this->is_dir($pwd))
        {
            $this->_pwd = explode("/", rtrim($pwd, "/"));
        } else
        {
            {
                throw new SFTPException("Could not access directory");
            }
        }
        return $this->pwd();
    }

    /**
     * check if path is directory
     *
     * @param $path
     * @return bool
     * @throws SFTPException
     */
    public function is_dir($path): bool
    {
        return ($this->_isRoot($path)) ? true : is_dir($this->_sftpGetPath($path));
    }

    /**
     * returns current remote working directory.
     *
     * @return string
     * @throws SFTPException
     */
    public function pwd(): string
    {

        if (is_null($this->_pwd) || empty($this->_pwd))
        {
            $pwd = $this->_realpath(".");
            $this->_pwd = explode("/", $pwd);
        }
        if (count($this->_pwd) > 1)
        {
            return implode("/", $this->_pwd);
        } else
        {
            return "/";
        }
    }

    /**
     * returns extended functions
     *
     * @return ISFTPExtended
     */
    public function getExtended(): ISFTPExtended
    {
        return new SFTPExtended($this);
    }
}
