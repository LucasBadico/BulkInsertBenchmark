<?php

class AdapterMongoDb extends AdapterGeneral implements Benchmarkable {
  private $mongo;
  private $collection;

  public function __construct(array $options) {
    $this->options = $options;

    $this->mongo = new Mongo("mongodb://" . $options["host"] . ":" .$options["port"]);
    $db = $this->mongo->selectDB($this->options["dbname"]);
    $db->command(array("dropDatabase" => 1));

    $this->collection = $this->mongo->selectCollection($this->options["dbname"], $this->options["collectionname"]);
  }

  public function getName() {
    return 'mongodb';
  }

  public function init() {
    $this->collection->drop();
    parent::init();
  }
  
  public function addDocuments(array $documents) {
    $start = microtime(true);
    $this->collection->batchInsert($documents, array("safe" => true));
    $this->totalTime += microtime(true) - $start;
  }
  
  public function getDocumentCount() {
    return $this->collection->count();
  }

  public function shutdown() {
    $this->collection->drop();
  }
  
  public function getFilesize() {
    sleep(3);
    clearstatcache();
  
    $info = $this->mongo->listDBs();
    foreach ($info["databases"] as $database) {
      if ($database["name"] == $this->options["dbname"]) {
        return $database["sizeOnDisk"];
      }
    }

    return 0;
  }

  public function getNextId() {
    return (string) (100000 + $this->id++);
  }

}
