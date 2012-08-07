<?php

class AdapterArangoDb extends AdapterGeneral implements Adapter {
  private $urlBase;

  public function __construct(array $options) {
    $this->options = $options;

    $this->urlBase = sprintf("http://%s:%s", $this->options["host"], $this->options["port"]);
  }

  public function getName() {
    return 'arangod';
  }

  public function init() {
    $this->send("DELETE", "/_api/collection/" . $this->options["collectionname"]);
    $this->send("POST", "/_api/collection", json_encode(array("name" => $this->options["collectionname"]), true));
    $this->send("PUT", "/_api/collection/" . $this->options["collectionname"] . "/truncate");

    parent::init();
  }
  
  public function addDocuments(array $documents) {
    $docs = "";
    foreach ($documents as $document) {
      $docs .= json_encode($document, true) . "\n";
    }

    $this->send("POST", "/_api/import?type=documents&useId=yes&collection=" . urlencode($this->options["collectionname"]), $docs);
  }

  public function getDocumentCount() {
    $info = $this->send("GET", "/_api/collection/" . $this->options["collectionname"] . "/figures");

    return $info["count"];
  }

  public function shutdown() {
    $this->send("DELETE", "/_api/collection/" . $this->options["collectionname"]);
  }
  
  public function getFilesize() {
    sleep(3);
    clearstatcache();

    $info = $this->send("GET", "/_api/collection/" . $this->options["collectionname"]);
    $id = $info["id"];

    $result = preg_match("/^(\d+)\s+/", shell_exec("du -bs " . escapeshellarg($this->options["datadir"] . "/collection-" . $id)), $matches);
    if ($result > 0) {
      return (int) $matches[1];
    }

    return NULL;
  }

  public function getNextId() {
    return (100000 + $this->id++);
  }

  private function send($method, $url, $data = NULL) {
    $options = array(
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_CUSTOMREQUEST => $method,
      CURLOPT_HTTPHEADER => array("Content-Type: application/json", "Connection: Close"),
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
      printf("ERROR: %s\n", curl_error($curl));
      $this->errors++;
      curl_close($curl);

      return NULL;
    }
    curl_close($curl);

    return json_decode($result, true);
  }

}
