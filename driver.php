<?php

function benchmarkAutoload($className) {
  require __DIR__ . "/" . $className . ".php";
}

spl_autoload_register("benchmarkAutoload");


$documentCounts = array(
  1000 => 1000,
  10000 => 1000,
  100000 => 1000,
  1000000 => 1000,
  10000000 => 1000,
);

$protoTypes = array(
  "short" => array("foo" => "bar"),
  "mid"   => array("foo" => "bar", "baz" => "barbaz", "sub" => array(1, 2, 3, 4, 5), "birds" => array("Robin", "Mockingbird", "Swan")),
);

$adapters = array();
$adapters[] =new AdapterCouchDb(array(
  "dbname" => "benchmark", 
  "host" => "127.0.0.1", 
  "port" => 5984, 
  "datafile" => "/performancetest/bin/couchdb/var/lib/couchdb/benchmark.couch",
));
$adapters[] = new AdapterMongoDb(array(
  "dbname" => "benchmark", 
  "collectionname" => "benchmark",
  "host" => "127.0.0.1", 
  "port" => 27017, 
));
$adapters[] = new AdapterArangoDb(array(
  "host" => "127.0.0.1", 
  "port" => 8529, 
  "collectionname" => "benchmark",
  "datadir" => "/performancetest/bin/arangodb/var/lib/data",
));

$benchmark = new InsertBenchmark();
$benchmark->addAdapters($adapters);

$benchmark::printHeaders();
foreach ($protoTypes as $protoTypeName => $protoTypeData) {
  foreach ($documentCounts as $documentCount => $blockSize) {
    $benchmark->run($documentCount, $blockSize, $protoTypeName, $protoTypeData, true);
  }
}

