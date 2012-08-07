<?php

class AdapterCouchDb extends AdapterGeneral implements Benchmarkable {
  private $path;
  private $urlBase;

  public function __construct(array $options) {
    $this->options = $options;
    $this->path = "/" . $this->options["dbname"];

    $this->urlBase = sprintf("http://%s:%s", $this->options["host"], $this->options["port"]);
  }

  public function getName() {
    return 'couchdb';
  }

  public function init() {
    $this->send("DELETE", $this->path);
    $this->send("PUT", $this->path);

    parent::init();
  }
  
  public function addDocuments(array $documents) {
    $this->send("POST", $this->path . "/_bulk_docs", array("docs" => $documents));
  }

  public function getDocumentCount() {
    $result = $this->send("GET", $this->path);

    return $result["doc_count"];
  }

  public function shutdown() {
    $this->send("DELETE", $this->path);
  }
  
  public function getFilesize() {
    sleep(3);
    clearstatcache();
    
    return filesize($this->options["datafile"]);
  }

  public function getNextId() {
    return (string) (100000 + $this->id++);
  }

  private function send($method, $url, array $data = NULL) {
    $options = array(
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_CUSTOMREQUEST => $method,
      CURLOPT_HTTPHEADER => array("Content-Type: application/json", "Connection: Close"),
    );
    
    if ($data !== NULL) {
      $options[CURLOPT_POSTFIELDS] = json_encode($data);
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
