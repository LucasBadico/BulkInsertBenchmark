<?php

class AdapterMongoDb extends AdapterGeneral implements Adapter {
  private $mongo;
  private $collection;

  const DEFAULT_PORT = 27017;

  public function __construct(array $options) {
    if (! isset($options["port"])) {
      $options["port"] = self::DEFAULT_PORT;
    }
    $this->options = $options;
    $this->mongo = new Mongo("mongodb://" . $this->options["host"] . ":" .$this->options["port"]);
    $db = $this->mongo->selectDB($this->options["dbname"]);
    $db->command(array("dropDatabase" => 1));

    $this->collection = $this->mongo->selectCollection($this->options["dbname"], $this->options["collectionname"]);
  }

  public function getName() {
    return 'mongodb';
  }
  
  public function getCollectionName() {
    return $this->options["dbname"] . "." . $this->options["collectionname"];
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
    
    $db = $this->mongo->selectDB($this->options["dbname"]);
    $db->command(array("dropDatabase" => 1));

    $this->collection = $this->mongo->selectCollection($this->options["dbname"], $this->options["collectionname"]);
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
    return (string) ($this->id++);
  }

  public function getVersion() {
    $admin = $this->mongo->admin;
    $info = $admin->command(array('buildinfo' => true));
    return $info['version'];
  }

  public function command($data) {
    return $this->selectCollection('$cmd')->findOne($data);
  }
}
