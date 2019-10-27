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
use Tez\PHPssh2\ISSH2Resource;

class ASFTP implements ISSH2Resource
{

    /**
     * @var array | null
     */
    protected $_pwd = null;
    /**
     * @var ISSH2
     */
    private $_ssh2 = null;
    /**
     * @var resource
     */
    private $_sftp = null;

    /**
     * SFTP constructor.
     * @param ISSH2 $ssh2
     */
    public function __construct(ISSH2 $ssh2)
    {
        $this->setSSH2Connection($ssh2);
    }

    /**
     * @param ISSH2 $ssh2
     */
    public function setSSH2Connection(ISSH2 $ssh2): void
    {
        $this->_ssh2 = $ssh2;
        $this->connectSFTP();
    }

    /**
     * connect to sftp server
     */
    private function connectSFTP()
    {
        $this->_sftp = ssh2_sftp($this->_ssh2->getConnection());
    }

    /**
     * returns human readable file permissons.
     *
     * @param int $perms
     * @return string
     */
    protected function _fileperms($perms): string
    {
        if (($perms & 0xC000) == 0xC000)
        {
            // Socket
            $info = 's';
        } else if (($perms & 0xA000) == 0xA000)
        {
            // Symbolischer Link
            $info = 'l';
        } else if (($perms & 0x8000) == 0x8000)
        {
            // Regulär
            $info = '-';
        } else if (($perms & 0x6000) == 0x6000)
        {
            // Block special
            $info = 'b';
        } else if (($perms & 0x4000) == 0x4000)
        {
            // Verzeichnis
            $info = 'd';
        } else if (($perms & 0x2000) == 0x2000)
        {
            // Character special
            $info = 'c';
        } else if (($perms & 0x1000) == 0x1000)
        {
            // FIFO pipe
            $info = 'p';
        } else
        {
            // Unknown
            $info = 'u';
        }

// Besitzer
        $info .= (($perms & 0x0100) ? 'r' : '-');
        $info .= (($perms & 0x0080) ? 'w' : '-');
        $info .= (($perms & 0x0040) ?
            (($perms & 0x0800) ? 's' : 'x') :
            (($perms & 0x0800) ? 'S' : '-'));

// Gruppe
        $info .= (($perms & 0x0020) ? 'r' : '-');
        $info .= (($perms & 0x0010) ? 'w' : '-');
        $info .= (($perms & 0x0008) ?
            (($perms & 0x0400) ? 's' : 'x') :
            (($perms & 0x0400) ? 'S' : '-'));

// Andere
        $info .= (($perms & 0x0004) ? 'r' : '-');
        $info .= (($perms & 0x0002) ? 'w' : '-');
        $info .= (($perms & 0x0001) ?
            (($perms & 0x0200) ? 't' : 'x') :
            (($perms & 0x0200) ? 'T' : '-'));

        return $info;
    }

    /**
     * returns unix filesystem like human readable timestamp.
     *
     * @param int $timestamp
     * @return string
     */
    protected function _timeStampToDate($timestamp): string
    {
        if ($timestamp > strtotime(date('Y-m-d H:i:s') . ' -6 month'))
        {
            $date = date('M j H:i', $timestamp);
        } else
        {
            $date = date('M j Y', $timestamp);
        }
        return $date;
    }

    /**
     * get real sftp path
     *
     * @param string $path
     * @return string
     * @throws SFTPException
     */
    protected function _realpath(string $path): string
    {
        return ssh2_sftp_realpath($this->_getSFTPResource(), $path);
    }

    /**
     * returns sftp resource
     *
     * @return resource
     * @throws SFTPException
     */
    protected function _getSFTPResource()
    {
        $this->_checkSFTPResource();
        return $this->_sftp;
    }

    /**
     * @throws SFTPException
     */
    protected function _checkSFTPResource(): void
    {
        if (is_null($this->_sftp) || !is_resource($this->_sftp))
        {
            throw new SFTPException('No SFTP resource available');
        }
    }

    /**
     * @return resource
     */
    protected function _getSSH2Resource()
    {
        return $this->_ssh2->getConnection();
    }

    /**
     * get remote directory information.
     *
     * @param string $path
     * @return array
     * @throws SFTPException
     */
    protected function _dir(string $path = ''): array
    {
        if (empty($path))
        {
            $path = ".";
        }
        $files = [];
        $handle = opendir($this->_sftpGetPath($path));
        // List all the files
        while ($handle && false !== ($file = readdir($handle)))
        {
            array_push($files, $file);
        }

        closedir($handle);
        sort($files);
        return $files;
    }

    /**
     * creates a path with sftp resource
     * @param string $path
     * @return string
     * @throws SFTPException
     */
    protected function _sftpGetPath(string $path): string
    {
        $sftp = $this->_getSFTPResource();
        $path = $this->_getPath($path);
        return 'ssh2.sftp://' . intval($sftp) . $path;
    }

    /**
     * returns absolute path of a given path.
     *
     * @param string $path
     * @return string
     */
    protected function _getPath(string $path): string
    {
        $split = explode("/", $path);
        $pwd = $this->_pwd;

        if (empty($split[0]))
        {
            return implode("/", $split);
        }

        foreach ($split as $key => $value)
        {
            if (false == empty($value) || $key == 0)
            {
                if ($value == "..")
                {
                    if (count($pwd) > 1)
                    {
                        array_pop($pwd);
                    }
                } else if ($value == ".")
                {

                } else if (empty($value))
                {
                    $pwd = [""];
                } else
                {
                    array_push($pwd, $value);
                }
            }
        }
        return implode("/", $pwd);
    }

    /**
     * check if path exits
     *
     * @param $path
     * @return bool
     * @throws SFTPException
     */
    protected function _checkIfExist($path)
    {
        return ($this->_isRoot($path)) ? true : (ssh2_sftp_stat($this->_getSFTPResource(), $this->_getPath($path))) ? true : false;
    }

    /**
     * check if path is root
     *
     * @param $path
     * @return bool
     */
    protected function _isRoot($path)
    {
        return ($path == '/');
    }
}