<?php

$renderers = array(
  new RendererConsole(),
  new RendererCsvFile("results.csv", ";"),
);

// chunk/bulk/batch size (number of documents to be loaded at once)
$chunkSize = 1000;

$dataProviders = array(
  // load 1000 documents with the same prototype. documents will get unique ids, though
  new DataProviderStatic("short_1000", 1000, $chunkSize, array("foo" => "bar")),
  // load 10000 documents with the same prototype. documents will get unique ids, though
  new DataProviderStatic("short_10000", 10000, $chunkSize, array("foo" => "bar")),
  // load 100000 documents with the same prototype. documents will get unique ids, though
  new DataProviderStatic("short_100000", 100000, $chunkSize, array("foo" => "bar")),
  // load 1000000 documents with the same prototype. documents will get unique ids, though
  new DataProviderStatic("short_1000000", 1000000, $chunkSize, array("foo" => "bar")),
  // load 10000000 documents with the same prototype. documents will get unique ids, though
  new DataProviderStatic("short_10000000", 10000000, $chunkSize, array("foo" => "bar")),
  // load documents from a JSON file. one json document per line!
  // new DataProviderJsonFile("enron", 41299, 5000, "/tmp/datasets/enron.json"),
);

$adapters = array(
    /*
  new AdapterCouchDb(array(
    // the name of the database the data will be loaded into. caution: it will be removed if it exists!
    "dbname" => "benchmark", 
    // CouchDB host name
    "host" => "127.0.0.1",
    // CouchDB HTTP port
    "port" => 5984, 
    // place where the CouchDB datafile resides, leave empty if client and server are on different hosts
    "datafile" => "/performancetest/bin/couchdb/var/lib/couchdb/benchmark.couch",
  )),
  new AdapterMongoDb(array(
    // the name of the database the data will be loaded into
    "dbname" => "benchmark", 
    // the name of the collection the data will be loaded into. caution: it will be removed if it exists!
    "collectionname" => "benchmark",
    // MongoDB host name
    "host" => "127.0.0.1",
    // MongoDB HTTP port
    "port" => 27017, 
  )),
  */
  new AdapterArangoDb(array(
    // the name of the collection the data will be loaded into. caution: it will be removed if it exists!
    "collectionname" => "benchmark",
    // ArangoDB host name
    "host" => "127.0.0.1",
    // ArangoDB port
    "port" => 8529 
  )),
);

