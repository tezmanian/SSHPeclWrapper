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

abstract class ASFTP implements ISFTPResource
{

    /**
     * @var array | null
     */
    protected $_pwd = [];
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
     * @inheritDoc
     */
    public function setSSH2Connection(ISSH2 $ssh2): void
    {
        $this->_ssh2 = $ssh2;
    }

    /**
     * @inheritDoc
     */
    public function getSSH2Connection(): ISSH2
    {
        return $this->_ssh2;
    }

    /**
     * connect to sftp server
     *
     * @return ISFTP
     */
    public function connect(): ISFTP
    {
        $this->_sftp = ssh2_sftp($this->getSSH2Connection()->getConnection());
        $this->pwd();
        return $this;
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
    public function _getPath(string $path): string
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

    /**
     * disconnect SFTP connection, not SSH2 connection
     */
    public function quit(): void
    {
        $this->_sftp = null;
        $this->_pwd = [];
    }

    /**
     * returns actual the pwd
     *
     * @return string
     */
    abstract public function pwd(): string;
}