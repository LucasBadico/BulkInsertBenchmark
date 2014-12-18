<?php

$renderers = array(
  new RendererConsole(),
  new RendererCsvFile("results.csv", ";"),
);

// chunk/bulk/batch size (number of documents to be loaded at once)
$chunkSize = 10000;
// the place where data files reside
$datasetPath = "./datasets/";

$dataProviders = array(
  new DataProviderStatic("uniform_1000", 1000, $chunkSize, array("foo" => "bar")),
  new DataProviderStatic("uniform_10000", 10000, $chunkSize, array("foo" => "bar")),
  new DataProviderStatic("uniform_100000", 100000, $chunkSize, array("foo" => "bar")),
  new DataProviderStatic("uniform_1000000", 1000000, $chunkSize, array("foo" => "bar")),
  new DataProviderJsonFile("aol_100000", 100000, $chunkSize, $datasetPath . "aol_part_100000.json"),
  new DataProviderJsonFile("aol_1000000", 1000000, $chunkSize, $datasetPath . "aol_part_1000000.json"),
  new DataProviderJsonFile("aol_10000000", 10000000, $chunkSize, $datasetPath . "aol_part_10000000.json"),
  new DataProviderJsonFile("campaign_10000", 10000, $chunkSize, $datasetPath . "campaign_part_10000.json"),
  new DataProviderJsonFile("campaign_100000", 100000, $chunkSize, $datasetPath . "campaign_part_100000.json"),
  new DataProviderJsonFile("campaign_1000000", 1000000, $chunkSize, $datasetPath . "campaign_part_1000000.json"),
  new DataProviderJsonFile("enron_1000", 1000, $chunkSize / 10, $datasetPath . "enron_part_1000.json"),
  new DataProviderJsonFile("enron_10000", 10000, $chunkSize / 10, $datasetPath . "enron_part_10000.json"),
  new DataProviderJsonFile("enron_100000", 100000, $chunkSize / 10, $datasetPath . "enron_part_100000.json"),
  new DataProviderJsonFile("names_1000", 1000, $chunkSize,  $datasetPath . "names_part_1000.json"),
  new DataProviderJsonFile("names_10000", 10000, $chunkSize,  $datasetPath . "names_part_10000.json"),
  new DataProviderJsonFile("names_100000", 100000, $chunkSize, $datasetPath . "names_part_100000.json"),
  new DataProviderJsonFile("wiki_1000", 1000, $chunkSize, $datasetPath . "wiki_part_1000.json"),
  new DataProviderJsonFile("wiki_10000", 10000, $chunkSize, $datasetPath . "wiki_part_10000.json"),
  new DataProviderJsonFile("wiki_100000", 100000, $chunkSize, $datasetPath . "wiki_part_100000.json")
);

$adapters = array(
  new AdapterArangoDb(array(
    "flavor" => "journal-size-32",
    "dbname" => "import",
    "collectionname" => "benchmark",
    "host" => "127.0.0.1",
    "journalsize" => 1048576 * 32
  )),
  new AdapterCouchDb(array(
    "dbname" => "import",
    "host" => "127.0.0.1",
    "datafile" => "/var/lib/couchdb/import.couch"
  )),
  new AdapterMongoDb(array(
    "dbname" => "import",
    "collectionname" => "benchmark",
    "host" => "127.0.0.1"
  )),
);

