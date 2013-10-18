<?php

class AdapterArangoDb extends AdapterGeneral implements Adapter {
  private $urlBase;

  const DEFAULT_PORT = 8529;

  public function __construct(array $options) {
    if (! isset($options["port"])) {
      $options["port"] = self::DEFAULT_PORT;
    }
    $this->options = $options;

    $this->urlBase = sprintf("http://%s:%s", $this->options["host"], $this->options["port"]);

    if (isset($this->options["dbname"])) {
      // drop existing database
      $this->send("DELETE", "/_db/_system/_api/database/" . urlencode($this->options["dbname"]));
      
      // re-create database 
      $this->send("POST", "/_db/_system/_api/database", json_encode(array("name" => $this->options["dbname"]), true));
    }
    
    if (isset($this->options["dbname"])) {
      $this->urlBase .= "/_db/" . urlencode($this->options["dbname"]);
    }
  }
  
  public function getCollectionName() {
    if (isset($this->options["dbname"])) {
      return $this->options["dbname"] . "." . $this->options["collectionname"];
    }
    return $this->options["collectionname"];
  }

  public function getName() {
    return 'arangodb';
  }
  
  public function init() {
    $options = array("name" => $this->options["collectionname"]);
    if (isset($this->options["journalsize"])) {
      $options["journalSize"] = (int) $this->options["journalsize"];
    }
    $this->send("DELETE", "/_api/collection/" . urlencode($this->options["collectionname"]));
    $this->send("POST", "/_api/collection", json_encode($options), true);

    parent::init();
  }
  
  public function addDocuments(array $documents) {
    $docs = "";
    foreach ($documents as $document) {
      $docs .= json_encode($document, true) . "\n";
    }

    $this->send("POST", "/_api/import?type=documents&collection=" . urlencode($this->options["collectionname"]), $docs);
  }

  public function getDocumentCount() {
    $info = $this->send("GET", "/_api/collection/" . urlencode($this->options["collectionname"]) . "/figures");

    return $info["count"];
  }

  public function shutdown() {
    $this->send("DELETE", "/_api/collection/" . urlencode($this->options["collectionname"]));
  }
  
  public function getFilesize() {
    $info = $this->send("GET", "/_api/collection/" . urlencode($this->options["collectionname"]) . "/figures");
    $fig = $info["figures"];

    return $fig["datafiles"]["fileSize"] + 
           $fig["journals"]["fileSize"] + 
           $fig["compactors"]["fileSize"] + 
           $fig["shapefiles"]["fileSize"];
  }

  public function getNextId() {
    return $this->id++;
  }

  public function getVersion() {
    $response = $this->send("GET", "/_api/version");
    return $response['version'];
  }

  private function send($method, $url, $data = NULL) {
    $options = array(
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_CUSTOMREQUEST => $method,
      CURLOPT_HTTPHEADER => array("Content-Type: application/json", "Connection: Keep-Alive"),
    );
   
    if ($data !== NULL) {
      $options[CURLOPT_POSTFIELDS] = $data;
    }
    
    $curl = curl_init($this->urlBase . $url);
    curl_setopt_array($curl, $options);

    $start = microtime(true);
    $result = curl_exec($curl);
    $this->totalTime += microtime(true) - $start;

    if ($result === false) {
      throw new Exception(sprintf("Adapter error: %s", curl_error($curl)));
      $this->errors++;
      curl_close($curl);

      return NULL;
    }
    curl_close($curl);

    return json_decode($result, true);
  }

}
