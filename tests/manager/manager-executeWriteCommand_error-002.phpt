--TEST--
MongoDB\Driver\Manager::executeWriteCommand() throws CommandException for invalid writeConcern
--SKIPIF--
<?php require __DIR__ . "/../utils/basic-skipif.inc"; ?>
<?php NEEDS('REPLICASET'); CLEANUP(REPLICASET); ?>
--FILE--
<?php
require_once __DIR__ . "/../utils/basic.inc";

$manager = new MongoDB\Driver\Manager(REPLICASET);

$command = new MongoDB\Driver\Command([
    'findAndModify' => COLLECTION_NAME,
    'query' => ['_id' => 'foo'],
    'update' => ['foo' => ['bar']],
    'upsert' => true,
    'new' => true,
]);

try {
    $manager->executeWriteCommand(DATABASE_NAME, $command, ['writeConcern' => new MongoDB\Driver\WriteConcern("undefined")]);
} catch (MongoDB\Driver\Exception\CommandException $e) {
    printf("%s(%d): %s\n", get_class($e), $e->getCode(), $e->getMessage());
    var_dump($e->getResultDocument());
}

?>
===DONE===
<?php exit(0); ?>
--EXPECTF--
MongoDB\Driver\Exception\CommandException(79): Write Concern error: No write concern mode named 'undefined' found in replica set configuration
object(stdClass)#13 (6) {
  ["lastErrorObject"]=>
  object(stdClass)#5 (3) {
    ["n"]=>
    int(1)
    ["updatedExisting"]=>
    bool(false)
    ["upserted"]=>
    string(3) "foo"
  }
  ["value"]=>
  object(stdClass)#6 (2) {
    ["_id"]=>
    string(3) "foo"
    ["foo"]=>
    array(1) {
      [0]=>
      string(3) "bar"
    }
  }
  ["writeConcernError"]=>
  object(stdClass)#7 (3) {
    ["code"]=>
    int(79)
    ["codeName"]=>
    string(23) "UnknownReplWriteConcern"
    ["errmsg"]=>
    string(74) "No write concern mode named 'undefined' found in replica set configuration"
  }
  ["ok"]=>
  float(1)
  ["operationTime"]=>
  object(MongoDB\BSON\Timestamp)#8 (2) {
    ["increment"]=>
    string(1) "%d"
    ["timestamp"]=>
    string(10) "%d"
  }
  ["$clusterTime"]=>
  object(stdClass)#12 (2) {
    ["clusterTime"]=>
    object(MongoDB\BSON\Timestamp)#9 (2) {
      ["increment"]=>
      string(1) "%d"
      ["timestamp"]=>
      string(10) "%d"
    }
    ["signature"]=>
    object(stdClass)#11 (2) {
      ["hash"]=>
      object(MongoDB\BSON\Binary)#10 (2) {
        ["data"]=>
        string(20) "%S"
        ["type"]=>
        int(0)
      }
      ["keyId"]=>
      int(0)
    }
  }
}
===DONE===
