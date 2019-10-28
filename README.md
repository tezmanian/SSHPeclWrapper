# SSHPeclWrapper
Simple Wrapper for SSH2 Connections


Little example

```php

$auth = new Tez\PHPssh2\Auth\SSH2AuthPassword('user', 'password');

$conn = new \Tez\PHPssh2\Connection\SSH2Connection('localhost');

$ssh2 = new \Tez\PHPssh2\SSH2($conn, $auth);

$sftp = $ssh2->getSFTP();

$pwd = $sftp->pwd();
```
