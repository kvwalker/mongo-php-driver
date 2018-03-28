MongoDB\Exception\SSLConnectionException
--TEST--
MongoDB\Driver\Exception\SSLConnectionException is deprecated
--INI--
error_reporting=-1
--FILE--
<?php
$exception = new MongoDB\Driver\Exception\SSLConnectionException();
var_dump($exception);

?>
===DONE===
<?php exit(0); ?>
--EXPECT--
bool(true)
===DONE===
