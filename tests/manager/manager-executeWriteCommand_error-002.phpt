--TEST--
MongoDB\Driver\Manager::executeWriteCommand() throws CommandException for invalid writeConcern
--SKIPIF--
<?php require __DIR__ . "/../utils/basic-skipif.inc"; ?>
<?php NEEDS('REPLICASET'); CLEANUP(REPLICASET); ?>
--FILE--
<?php
require_once __DIR__ . "/../utils/basic.inc";

$manager = new MongoDB\Driver\Manager(REPLICASET);

$document = ['foo' => ['bar']];

$command = new MongoDB\Driver\Command([
    'findAndModify' => COLLECTION_NAME,
    'query' => ['_id' => 'foo'],
    'update' => $document,
    'upsert' => true,
    'new' => true,
]);

try {
    $manager->executeWriteCommand(DATABASE_NAME, $command, ['writeConcern' => new MongoDB\Driver\WriteConcern("minority")]);
} catch (MongoDB\Driver\Exception\CommandException $e) {
	printf("CommandException: %s\n", $e->getMessage());

    echo "\n===> ResultDocument\n";
    var_dump($e->getResultDocument());
}

?>
===DONE===
<?php exit(0); ?>
--EXPECT--
CommandException: Write Concern error: No write concern mode named 'minority' found in replica set configuration

===> ResultDocument
NULL
===DONE===
