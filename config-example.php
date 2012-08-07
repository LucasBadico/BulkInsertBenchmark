<?php

$dataProviders = array(
  new DataProviderRandomColumns("random", 1000, 5),
  new DataProviderStatic("short", 1000, array("foo" => "bar")),
  new DataProviderStatic("short", 10000, array("foo" => "bar")),
  new DataProviderStatic("short", 100000, array("foo" => "bar")),
  new DataProviderStatic("short", 1000000, array("foo" => "bar")),
  new DataProviderStatic("short", 10000000, array("foo" => "bar")),
  new DataProviderJsonFile("enron", 5000, "/tmp/datasets/enron.json"),
);

$adapters = array(
  new AdapterCouchDb(array(
    "dbname" => "benchmark", 
    "host" => "127.0.0.1",
    "port" => 5984, 
    "datafile" => "/performancetest/bin/couchdb/var/lib/couchdb/benchmark.couch",
  )),
  new AdapterMongoDb(array(
    "dbname" => "benchmark", 
    "collectionname" => "benchmark",
    "host" => "127.0.0.1",
    "port" => 27017, 
  )),
  new AdapterArangoDb(array(
    "host" => "127.0.0.1",
    "port" => 8529, 
    "collectionname" => "benchmark",
    "datadir" => "/performancetest/bin/arangodb/var/lib/data",
  )),
);

