--TEST--
MongoDB\Driver\Manager::executeWriteCommand() throws CommandException for invalid writeConcern
--SKIPIF--
<?php require __DIR__ . "/../utils/basic-skipif.inc"; ?>
<?php NEEDS('REPLICASET'); ?>
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
    assert($e->getMessage() === "Write Concern error: " . $e->getResultDocument()->writeConcernError->errmsg);
    assert($e->getCode() === $e->getResultDocument()->writeConcernError->code);
}

?>
===DONE===
<?php exit(0); ?>
--EXPECT--
MongoDB\Driver\Exception\CommandException(79): Write Concern error: No write concern mode named 'undefined' found in replica set configuration
===DONE===
